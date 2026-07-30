@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded relative transition-opacity duration-300">
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button"
            onclick="closeAlert()"
            class="absolute top-2 right-2 text-red-700 hover:text-red-900">
            ✕
        </button>

        <script>
            function closeAlert() {
                const el = document.getElementById('error-alert');
                if (!el) return;

                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }

            // auto close setelah 1 detik
            setTimeout(() => {
                closeAlert();
            }, 1000);
        </script>
    </div>
@endif