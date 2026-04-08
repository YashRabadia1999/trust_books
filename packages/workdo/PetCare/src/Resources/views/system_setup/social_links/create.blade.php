{{ Form::open(['route' => 'petcare.social.links.store', 'method' => 'POST', 'class' => 'needs-validation', 'novalidate']) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12" id="icons">
            {{ Form::label('social_media_icon', __('Social Media Icon'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-group col-md-12">
                <input type="text" id="icon-search" class="form-control mb-4" placeholder="{{ __('search . .') }}">
            </div>
            <div class="form-group col-md-12">
                <div class="i-main" id="icon-wrapper"
                    style="max-height: 100px; overflow-y: auto; display: flex; flex-wrap: wrap; gap: 10px;">
                </div>
            </div>
            <div class="form-group col-md-12">
                <input type="text" id="icon-input" name="social_media_icon" class="form-control"
                    placeholder="{{ __('Selected Icon') }}" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('social_media_name', __('Social Media Name'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('social_media_name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Social Media Name')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('social_media_link', __('Social Media Link'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::url('social_media_link', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('https://example.com')]) }}
                <small class="text-danger d-block mt-2">
                    {{ __('Note: Please enter the full URL including https:// or http://') }}
                </small>    
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}


{{-- Service Icon Js --}}
<script type="text/javascript">
    var iconlist = [
                        'fa-facebook', 'fa-twitter', 'fa-instagram', 'fa-linkedin', 'fa-youtube', 'fa-pinterest',
                        'fa-tiktok', 'fa-snapchat', 'fa-whatsapp', 'fa-telegram', 'fa-discord', 'fa-reddit',
                        'fa-skype', 'fa-tumblr', 'fa-vk', 'fa-weibo', 'fa-medium', 'fa-flickr', 'fa-dribbble',
                        'fa-behance', 'fa-vimeo', 'fa-slack',
                        'fa-rss', 'fa-envelope', 'fa-comments', 'fa-share-alt', 'fa-share-square', 'fa-paper-plane',
                        'fa-bullhorn', 'fa-broadcast-tower', 'fa-comment-alt', 'fa-globe', 'fa-hashtag',
                        'fa-x-twitter', 'fa-threads', 'fa-mastodon', 'fa-quora', 'fa-line',
                        'fa-soundcloud', 'fa-mixcloud', 'fa-stack-overflow', 'fa-github', 'fa-dev',
                        'fa-kickstarter', 'fa-wordpress'
                    ];


    var iconWrapper = document.getElementById('icon-wrapper');
    var iconSearch = document.getElementById('icon-search');
    var iconInput = document.getElementById('icon-input');

    function filterIcons(searchText) {
        iconWrapper.innerHTML = '';

        iconlist.forEach(function(iconClass) {
            if (iconClass.toLowerCase().includes(searchText)) {
                const iconDiv = document.createElement('div');
                const iconElement = document.createElement('i');

                const isBrand = ['fa-facebook', 'fa-twitter', 'fa-x-twitter', 'fa-instagram', 'fa-linkedin', 'fa-youtube', 'fa-pinterest','fa-tiktok', 'fa-snapchat', 'fa-whatsapp', 'fa-telegram', 'fa-discord', 'fa-reddit','fa-skype', 'fa-tumblr', 'fa-vk', 'fa-weibo', 'fa-medium', 'fa-flickr', 'fa-dribbble','fa-behance', 'fa-vimeo', 'fa-slack', 'fa-threads', 'fa-mastodon', 'fa-quora', 'fa-line','fa-soundcloud', 'fa-mixcloud', 'fa-stack-overflow', 'fa-github', 'fa-dev', 'fa-rumble','fa-kickstarter', 'fa-wordpress'].includes(iconClass);

                iconElement.classList.add(isBrand ? 'fab' : 'fas', iconClass);
                iconElement.style.fontSize = '24px';

                iconDiv.classList.add('i-block');
                iconDiv.setAttribute('data-clipboard-text', (isBrand ? 'fab' : 'fas') + ' ' + iconClass);
                iconDiv.setAttribute('data-bs-toggle', 'tooltip');
                iconDiv.setAttribute('title', (isBrand ? 'fab' : 'fas') + ' ' + iconClass);
                iconDiv.style.cursor = 'pointer';
                iconDiv.style.padding = '6px';
                iconDiv.style.borderRadius = '5px';
                iconDiv.style.display = 'flex';
                iconDiv.style.alignItems = 'center';
                iconDiv.style.justifyContent = 'center';
                iconDiv.style.width = '40px';
                iconDiv.style.height = '40px';

                iconDiv.addEventListener('click', function() {
                    document.getElementById('icon-input').value = (isBrand ? 'fab' : 'fas') + ' ' + iconClass;
                });


                iconDiv.appendChild(iconElement);

                iconWrapper.appendChild(iconDiv);
            }
        });

    }

    filterIcons('');

    iconSearch.addEventListener('keyup', function() {
        var searchText = iconSearch.value.toLowerCase();
        filterIcons(searchText);
    });
</script>