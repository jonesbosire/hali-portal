<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FileServeController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminMemberController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\Admin\AdminOpportunityController;
use App\Http\Controllers\Admin\AdminInvitationController;
use App\Http\Controllers\Admin\AdminBulletinController;
use App\Http\Controllers\Admin\AdminMembershipTierController;
use App\Http\Controllers\Admin\AdminOrganizationController;
use App\Http\Controllers\Admin\AdminQuickBooksController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Logout
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

// Welcome / landing page
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// Serve private uploaded files (auth required — files are outside web root)
Route::get('/files/{path}', [FileServeController::class, 'serve'])
    ->where('path', '.*')
    ->middleware('auth')
    ->name('files.serve');

// Invitation acceptance (guest) — rate limited against brute force
Route::get('/invitation/{token}', [InvitationController::class, 'show'])->name('invitation.show');
Route::post('/invitation/{token}', [InvitationController::class, 'accept'])
    ->middleware('throttle:invitation')
    ->name('invitation.accept');

// ── Stripe webhook ────────────────────────────────────────────────────────────
Route::post('/webhooks/stripe', [BillingController::class, 'webhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->middleware('throttle:60,1')
    ->name('webhooks.stripe');

// ── Flutterwave webhook ───────────────────────────────────────────────────────
// Outside auth + CSRF — signature verified via verif-hash header inside controller.
Route::post('/webhooks/flutterwave', [PaymentController::class, 'webhook'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class])
    ->middleware('throttle:60,1')
    ->name('webhooks.flutterwave');

// Authenticated member routes
Route::middleware(['auth', 'verified', 'active.user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Member Directory
    Route::get('/directory', [DirectoryController::class, 'index'])->name('directory.index');
    Route::get('/directory/{slug}', [DirectoryController::class, 'show'])->name('directory.show');

    // Events
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event:slug}/register', [EventController::class, 'register'])->middleware('throttle:event-register')->name('events.register');
    Route::delete('/events/{event:slug}/cancel', [EventController::class, 'cancelRegistration'])->name('events.cancel');

    // Stories & Posts
    Route::get('/stories', [PostController::class, 'index'])->name('posts.index');
    Route::get('/stories/{post:slug}', [PostController::class, 'show'])->name('posts.show');

    // Opportunities — static routes MUST come before wildcard {opportunity}
    Route::get('/opportunities', [OpportunityController::class, 'index'])->name('opportunities.index');
    Route::get('/opportunities/create', [OpportunityController::class, 'create'])->name('opportunities.create');
    Route::post('/opportunities', [OpportunityController::class, 'store'])->middleware('throttle:opportunities')->name('opportunities.store');
    Route::get('/opportunities/{opportunity}', [OpportunityController::class, 'show'])->name('opportunities.show');

    // Resources
    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    Route::get('/resources/{resource}/download', [ResourceController::class, 'download'])->name('resources.download');

    // Profile — split into separate forms for security (profile info vs password)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/organization', [ProfileController::class, 'organization'])->name('organization.edit');
    Route::patch('/organization', [ProfileController::class, 'updateOrganization'])->name('organization.update');

    // Billing
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::post('/billing/portal', [BillingController::class, 'portal'])->name('billing.portal');

    // Flutterwave membership dues payment
    Route::post('/billing/pay', [PaymentController::class, 'initiate'])->name('payment.initiate');
    Route::get('/billing/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');

    // Notifications
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');

    Route::post('/notifications/{id}/read', function ($id) {
        if ($id === 'all') {
            auth()->user()->unreadNotifications->markAsRead();
        } else {
            auth()->user()->notifications()->findOrFail($id)->markAsRead();
        }
        return back();
    })->name('notifications.read');
});

// Admin routes (super_admin + secretariat)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Membership Tiers
    Route::get('/tiers', [AdminMembershipTierController::class, 'index'])->name('tiers.index');
    Route::get('/tiers/create', [AdminMembershipTierController::class, 'create'])->name('tiers.create');
    Route::post('/tiers', [AdminMembershipTierController::class, 'store'])->name('tiers.store');
    Route::get('/tiers/{tier}/edit', [AdminMembershipTierController::class, 'edit'])->name('tiers.edit');
    Route::put('/tiers/{tier}', [AdminMembershipTierController::class, 'update'])->name('tiers.update');
    Route::post('/tiers/{tier}/toggle', [AdminMembershipTierController::class, 'toggleActive'])->name('tiers.toggle');
    Route::delete('/tiers/{tier}', [AdminMembershipTierController::class, 'destroy'])->name('tiers.destroy');

    // Members
    Route::get('/members', [AdminMemberController::class, 'index'])->name('members.index');
    Route::get('/members/{user}', [AdminMemberController::class, 'show'])->name('members.show');
    Route::patch('/members/{user}/status', [AdminMemberController::class, 'updateStatus'])->name('members.status');

    // Invitations
    Route::get('/invitations', [AdminInvitationController::class, 'index'])->name('invitations.index');
    Route::post('/invitations', [AdminInvitationController::class, 'store'])->name('invitations.store');
    Route::delete('/invitations/{invitation}', [AdminInvitationController::class, 'destroy'])->name('invitations.destroy');

    // Events
    Route::resource('events', AdminEventController::class);
    Route::post('/events/{event}/attendees/{registration}/attend', [AdminEventController::class, 'markAttended'])->name('events.attend');
    Route::get('/events/{event}/export', [AdminEventController::class, 'exportAttendees'])->name('events.export');

    // Event programs (agenda)
    Route::post('/events/{event}/programs', [AdminEventController::class, 'storeProgram'])->name('events.programs.store');
    Route::delete('/events/{event}/programs/{program}', [AdminEventController::class, 'destroyProgram'])->name('events.programs.destroy');

    // Posts
    Route::resource('posts', AdminPostController::class);

    // Opportunities
    Route::resource('opportunities', AdminOpportunityController::class);

    // Organizations (quick-create from invitation form)
    Route::post('/organizations', [AdminOrganizationController::class, 'store'])->name('organizations.store');

    // Bulletins
    Route::resource('bulletins', AdminBulletinController::class);
    Route::post('/bulletins/{bulletin}/send', [AdminBulletinController::class, 'send'])->name('bulletins.send');

    // QuickBooks Online integration
    Route::get('/quickbooks', [AdminQuickBooksController::class, 'index'])->name('quickbooks.index');
    Route::post('/quickbooks/authorize', [AdminQuickBooksController::class, 'authorize'])->name('quickbooks.authorize');
    Route::get('/quickbooks/callback', [AdminQuickBooksController::class, 'callback'])->name('quickbooks.callback');
    Route::delete('/quickbooks/disconnect', [AdminQuickBooksController::class, 'disconnect'])->name('quickbooks.disconnect');

    // ── Super admin only ───────────────────────────────────────────────────
    // Destructive member operations require super_admin — secretariat cannot delete users
    Route::middleware('super_admin')->group(function () {
        Route::delete('/members/{user}', [AdminMemberController::class, 'destroy'])->name('members.destroy');
    });
});

require __DIR__.'/auth.php';
