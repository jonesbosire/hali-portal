<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Expired — HALI Access Network</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-hali-bg flex items-center justify-center px-4">

<div class="max-w-md w-full text-center">
    <div class="bg-white rounded-2xl border border-hali-border shadow-card p-10">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-5">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center mx-auto mb-4">
            <span class="text-primary font-black text-lg">H</span>
        </div>

        <h1 class="text-xl font-bold text-hali-text-primary mb-2">Invitation Expired</h1>
        <p class="text-sm text-hali-text-secondary leading-relaxed mb-6">
            This invitation link has expired or has already been used.
            @if(isset($invitation) && $invitation->isAccepted())
                Your account has already been created — please sign in.
            @else
                Invitations are valid for 7 days. Please contact the HALI Secretariat to request a new invitation.
            @endif
        </p>

        <div class="space-y-3">
            @if(isset($invitation) && $invitation->isAccepted())
                <a href="{{ route('login') }}"
                   class="block w-full bg-primary text-white font-semibold py-2.5 rounded-xl text-sm hover:bg-primary-dark transition-colors">
                    Sign In to Portal
                </a>
            @else
                <a href="mailto:secretariat@haliaccess.net?subject=New Invitation Request"
                   class="block w-full bg-primary text-white font-semibold py-2.5 rounded-xl text-sm hover:bg-primary-dark transition-colors">
                    Request New Invitation
                </a>
                <a href="{{ route('login') }}"
                   class="block w-full border border-hali-border text-hali-text-secondary font-medium py-2.5 rounded-xl text-sm hover:bg-gray-50 transition-colors">
                    Sign In
                </a>
            @endif
        </div>

        <p class="mt-6 text-xs text-hali-text-secondary">
            Need help? Email
            <a href="mailto:secretariat@haliaccess.net" class="text-primary hover:underline">secretariat@haliaccess.net</a>
        </p>
    </div>
</div>

</body>
</html>
