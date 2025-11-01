<pre class="text-white bg-dark p-2 rounded">
    Avatar: {{ var_export($user->avatar, true) }}

    Exists on disk public?
    @php
        $exists = is_string($user->avatar) && \Storage::disk('public')->exists($user->avatar);
        echo $exists ? 'YES' : 'NO';
    @endphp
</pre>
