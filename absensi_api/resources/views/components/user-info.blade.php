@if(Auth::check())
    <script>
        const user_id = {{ Auth::id() }};
        const username = "{{ Auth::user()->username }}";
    </script>
@else
    <script>
        const user_id = null;
        const username = null;
    </script>
@endif
