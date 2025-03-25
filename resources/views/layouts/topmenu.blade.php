<div class="col-1">
    <div class="listNav">
        <div class="navigationBlock">
            <a class="navbar-brand" href="{{ url('home') }}"><img src="/images/dashboard_Inactive_btn.svg"> Dashboard</a>
            <a class="navbar-brand" href="{{ url('devices') }}"><img src="/images/devices_Inactive_btn.svg"> Devices</a>
            <a class="navbar-brand" href="{{ url('files') }}"><img src="/images/media_Inactive_btn.svg"> Media</a>
            <a class="navbar-brand" href="{{ url('playlists') }}"><img src="/images/playlist_Inactive_btn.svg"> Playlist</a>
            <a class="navbar-brand" href="{{ url('publish') }}"><img src="/images/publish_Inactive_btn.svg">  Publish</a>
            <a class="navbar-brand" href="{{ url('templates') }}"><img src="/images/template_Inactive_btn.svg"> Template</a>
            <a class="navbar-brand" href="{{ url('queue') }}"><img src="/images/queue_Inactive_btn.svg"> Queue</a>
            @if (auth()->user()->hasRole('super_admin'))
            {{-- <a class="navbar-brand" href="{{ url('settings') }}"><img src="/images/settings_Inactive_btn.svg"> Settings</a> --}}
            @endif
        </div>
    </div>
</div>