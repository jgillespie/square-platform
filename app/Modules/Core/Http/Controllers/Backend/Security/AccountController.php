<?php

namespace App\Modules\Core\Http\Controllers\Backend\Security;

use App\Http\Controllers\Controller;
use App\Modules\Core\Models\Account;
use App\Modules\Core\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('core.saccountdeny')->only('edit', 'update', 'destroy');
    }

    /**
     * Display a listing of the account.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('core::backend.security.account.index');
    }

    /**
     * Process account index datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexData()
    {
        $model = Account::query();

        return DataTables::eloquent($model)
            ->editColumn('is_enabled', function ($model) {
                if ($model->is_enabled) {
                    $text = __('Enabled');
                    $html = <<<HTML
                        <span class="badge badge-info">$text</span>
HTML;

                    return $html;
                }

                $text = __('Disabled');
                $html = <<<HTML
                    <span class="badge badge-secondary">$text</span>
HTML;

                return $html;
            })
            ->editColumn('is_backend', function ($model) {
                if ($model->is_backend) {
                    $text = __('Backend');
                    $html = <<<HTML
                        <span class="badge badge-info">$text</span>
HTML;

                    return $html;
                }

                $text = __('Frontend');
                $html = <<<HTML
                    <span class="badge badge-secondary">$text</span>
HTML;

                return $html;
            })
            ->editColumn('email_verified_at', function ($model) {
                if ($model->hasVerifiedEmail()) {
                    $text = __('Verified');
                    $html = <<<HTML
                        <span class="badge badge-secondary">$text</span>
HTML;

                    return $html;
                }

                $text = __('Unverified');
                $html = <<<HTML
                    <span class="badge badge-info">$text</span>
HTML;

                return $html;
            })
            ->editColumn('created_at', function ($model) {
                return $model->created_at->format('d-m-Y');
            })
            ->addColumn('roles', function ($model) {
                $roles = [];

                foreach ($model->roles as $role) {
                    array_push($roles, $role->name);
                }

                if (count($roles) === 0) {
                    return '-';
                }

                return implode(', ', $roles);
            })
            ->addColumn('actions', function ($model) {
                $editRoute = route('backend.security.account.edit', ['account' => $model->id]);
                $editText = __('Edit');
                $html = <<<HTML
                    <a class="btn btn-primary btn-sm btn-icon-split mr-2" href="$editRoute">
                        <span class="icon text-white-50"><i class="far fa-edit"></i></span>
                        <span class="text">$editText</span>
                    </a>
HTML;

                $destroyRoute = route('backend.security.account.destroy', ['account' => $model->id]);
                $destroyText = __('Delete');
                $html .= <<<HTML
                    <a class="btn btn-danger btn-sm btn-icon-split" href="$destroyRoute"
                        onclick="event.preventDefault(); deleteAccount($model->id, '$destroyRoute');">
                        <span class="icon text-white-50"><i class="far fa-trash-alt"></i></span>
                        <span class="text">$destroyText</span>
                    </a>
HTML;

                return $html;
            })
            ->rawColumns(['is_enabled', 'is_backend', 'email_verified_at', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new account.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('name', '!=', 'super')->get();

        return view('core::backend.security.account.create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created account in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $roles = Role::where('name', '!=', 'super')->pluck('id')->toArray();

        $validatedData = $request->validate([
            'is_enabled' => 'required|boolean',
            'is_backend' => 'required|boolean',
            'email' => 'required|string|email|max:255|unique:accounts',
            'email_verified' => 'sometimes|accepted',
            'roles' => [
                'sometimes',
                'array',
                Rule::in($roles),
            ],
        ]);

        $account = new Account();
        $account->is_enabled = $validatedData['is_enabled'];
        $account->is_backend = $validatedData['is_backend'];
        $account->email = $validatedData['email'];
        $account->password = Hash::make(Str::random(8));
        $account->save();

        if ($request->has('email_verified')) {
            $account->markEmailAsVerified();
        }

        if ($request->has('roles')) {
            $account->roles()->attach($validatedData['roles']);
        }

        return redirect()
            ->route('backend.security.account.index')
            ->with('status', __('The account has been created.'));
    }

    /**
     * Show the form for editing the specified account.
     *
     * @param  \App\Modules\Core\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $account)
    {
        $roles = Role::where('name', '!=', 'super')->get();

        return view('core::backend.security.account.edit', [
            'account' => $account,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the specified account in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modules\Core\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        $roles = Role::where('name', '!=', 'super')->pluck('id')->toArray();

        $validatedData = $request->validate([
            'is_enabled' => 'required|boolean',
            'is_backend' => 'required|boolean',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('accounts')->ignore($account->id),
            ],
            'email_verified' => 'sometimes|accepted',
            'roles' => [
                'sometimes',
                'array',
                Rule::in($roles),
            ],
        ]);

        $account->is_enabled = $validatedData['is_enabled'];
        $account->is_backend = $validatedData['is_backend'];
        $account->email = $validatedData['email'];
        $account->email_verified_at = null;
        $account->save();

        if ($request->has('email_verified')) {
            $account->markEmailAsVerified();
        }

        if ($request->has('roles')) {
            $account->roles()->sync($validatedData['roles']);
        } else {
            $account->roles()->sync([]);
        }

        return redirect()
            ->back()
            ->with('status', __('The account has been updated.'));
    }

    /**
     * Remove the specified account from storage.
     *
     * @param  \App\Modules\Core\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()
            ->back()
            ->with('status', __('The account has been deleted.'));
    }
}
