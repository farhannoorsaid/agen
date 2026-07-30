@if (session('success'))
    <div id="success-alert"
        class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded relative transition-opacity duration-300">

        <span>{{ session('success') }}</span>

        <button type="button"
            onclick="closeAlert()"
            class="absolute top-2 right-2 text-green-700 hover:text-green-900">
            ✕
        </button>
    </div>

    <script>
        function closeAlert() {
            const el = document.getElementById('success-alert');
            if (!el) return;

            el.style.opacity = '0';
            setTimeout(() => el.remove(), 300);
        }

        // auto close setelah 5 detik
        setTimeout(() => {
            closeAlert();
        }, 5000);
    </script>
@endif