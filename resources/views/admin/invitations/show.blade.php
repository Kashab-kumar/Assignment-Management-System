<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share Invitation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #ffffff; }
        .header { background: #9C27B0; color: white; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header-content { max-width: 800px; margin: 0 auto; }
        .container { max-width: 800px; margin: 20px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .success-icon { text-align: center; font-size: 64px; color: #4CAF50; margin-bottom: 20px; }
        h2 { text-align: center; color: #333; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 30px; }
        .info-box { background: #f0f7ff; padding: 15px; border-radius: 5px; border-left: 4px solid #2196F3; margin-bottom: 20px; }
        .info-box p { margin: 5px 0; color: #555; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; color: white; }
        .badge-teacher { background: #2196F3; }
        .badge-student { background: #4CAF50; }
        .link-box { background: #f8f9fa; padding: 15px; border-radius: 5px; border: 2px dashed #ddd; margin-bottom: 30px; }
        .link-box input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; font-family: monospace; }
        .share-section { margin-bottom: 30px; }
        .share-section h3 { color: #333; margin-bottom: 15px; text-align: center; }
        .share-buttons { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; }
        .share-btn { display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px 20px; border: none; border-radius: 8px; font-size: 16px; font-weight: bold; color: white; cursor: pointer; text-decoration: none; transition: transform 0.2s; }
        .share-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .share-btn svg { width: 24px; height: 24px; fill: currentColor; }
        .btn-whatsapp { background: #25D366; }
        .btn-messenger { background: #0084FF; }
        .btn-email { background: #EA4335; }
        .btn-copy { background: #666; }
        .btn-copy.copied { background: #4CAF50; }
        .btn-telegram { background: #0088cc; }
        .btn-back { display: inline-block; padding: 12px 24px; background: #9C27B0; color: white; text-decoration: none; border-radius: 4px; margin-top: 20px; }
        .btn-back:hover { background: #7B1FA2; }
        .alert-success { padding: 15px; background: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>Share Invitation Link</h1>
        </div>
    </div>

    <div class="container">
        @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="success-icon">✓</div>
            <h2>Invitation Created Successfully!</h2>
            <p class="subtitle">Share this link with {{ $invitation->email }}</p>

            <div class="info-box">
                <p><strong>Role:</strong> <span class="badge badge-{{ $invitation->role }}">{{ ucfirst($invitation->role) }}</span></p>
                <p><strong>Created:</strong> {{ $invitation->created_at->format('F d, Y') }}</p>
                <p><strong>Expires:</strong> {{ $invitation->expires_at->format('F d, Y') }} ({{ $invitation->expires_at->diffForHumans() }})</p>
                <p><strong>Status:</strong> 
                    @if($invitation->used)
                        <span class="badge badge-used">Used</span>
                    @else
                        <span class="badge badge-active">Active</span>
                    @endif
                </p>
            </div>

            <div class="link-box">
                <input type="text" id="inviteLink" value="{{ $inviteLink }}" readonly onclick="this.select()">
            </div>

            <div class="share-section">
                <h3>Share via:</h3>
                <div class="share-buttons">
                    <a href="#" class="share-btn btn-whatsapp" onclick="shareWhatsApp(); return false;">
                        <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp
                    </a>

                    <a href="#" class="share-btn btn-messenger" onclick="shareMessenger(); return false;">
                        <svg viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.654V24l4.088-2.242c1.092.3 2.246.464 3.443.464 6.627 0 12-4.974 12-11.111C24 4.974 18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8l3.131 3.259L19.752 8l-6.561 6.963z"/></svg>
                        Messenger
                    </a>

                    <a href="#" class="share-btn btn-telegram" onclick="shareTelegram(); return false;">
                        <svg viewBox="0 0 24 24"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>
                        Telegram
                    </a>

                    <a href="#" class="share-btn btn-email" onclick="shareEmail(); return false;">
                        <svg viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                        Email
                    </a>

                    <button class="share-btn btn-copy" id="copyBtn" onclick="copyToClipboard()">
                        <svg viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
                        <span id="copyText">Copy Link</span>
                    </button>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('admin.invitations.index') }}" class="btn-back">← Back to Invitations</a>
            </div>
        </div>
    </div>

    <script>
        const inviteLink = "{{ $inviteLink }}";
        const role = "{{ ucfirst($invitation->role) }}";

        function shareWhatsApp() {
            const message = `Hi! You've been invited to join our Assignment Management System as a ${role}.\n\nClick this link to complete your registration:\n${inviteLink}\n\nThis link expires in 30 days.`;
            const url = `https://wa.me/?text=${encodeURIComponent(message)}`;
            window.open(url, '_blank');
        }

        function shareMessenger() {
            const url = `https://www.facebook.com/dialog/send?link=${encodeURIComponent(inviteLink)}&app_id=YOUR_APP_ID&redirect_uri=${encodeURIComponent(window.location.href)}`;
            window.open(url, '_blank', 'width=600,height=400');
        }

        function shareTelegram() {
            const message = `Hi! You've been invited to join our Assignment Management System as a ${role}. Click this link to complete your registration: ${inviteLink}`;
            const url = `https://t.me/share/url?url=${encodeURIComponent(inviteLink)}&text=${encodeURIComponent(message)}`;
            window.open(url, '_blank');
        }

        function shareEmail() {
            const subject = `Invitation to Assignment Management System - ${role}`;
            const body = `Hi,\n\nYou've been invited to join our Assignment Management System as a ${role}.\n\nClick the link below to complete your registration:\n${inviteLink}\n\nThis invitation will expire in 30 days.\n\nBest regards,\nAssignment Management Team`;
            const url = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
            window.location.href = url;
        }

        function copyToClipboard() {
            const input = document.getElementById('inviteLink');
            input.select();
            input.setSelectionRange(0, 99999); // For mobile devices

            navigator.clipboard.writeText(inviteLink).then(() => {
                const btn = document.getElementById('copyBtn');
                const text = document.getElementById('copyText');
                
                btn.classList.add('copied');
                text.textContent = 'Copied!';
                
                setTimeout(() => {
                    btn.classList.remove('copied');
                    text.textContent = 'Copy Link';
                }, 2000);
            }).catch(err => {
                // Fallback for older browsers
                document.execCommand('copy');
                alert('Link copied to clipboard!');
            });
        }
    </script>
</body>
</html>
