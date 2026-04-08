{!! Form::model($FAQs, [
    'route' => ['petcare.faq.update', $faqId],
    'method' => 'PUT',
    'class' => 'needs-validation',
    'novalidate',
]) !!}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12" id="icons">
            {{ Form::label('faq_icon', __('FAQ Icon'), ['class' => 'form-label']) }}<x-required></x-required>
            <div class="form-group col-md-12">
                <input type="text" id="icon-search" class="form-control mb-4" placeholder="{{ __('search . .') }}">
            </div>
            <div class="form-group col-md-12">
                <div class="i-main" id="icon-wrapper"
                    style="max-height: 100px; overflow-y: auto; display: flex; flex-wrap: wrap; gap: 10px;">
                </div>
            </div>
            <div class="form-group col-md-12">
                <input type="text" id="icon-input" name="faq_icon" class="form-control"
                    placeholder="{{ __('Selected Icon') }}" readonly value="{{ old('faq_icon', $FAQs->faq_icon) }}">
            </div>
        </div>        
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('faq_topic', __('FAQ Topic'), ['class' => 'form-label']) }}<x-required></x-required>
                {{ Form::text('faq_topic', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Name')]) }}
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
                        'fa-question-circle', 'fa-info-circle', 'fa-lightbulb', 'fa-comments',
                        'fa-comment-dots', 'fa-book', 'fa-book-open', 'fa-clipboard-question',
                        'fa-life-ring', 'fa-question', 'fa-headset', 'fa-info', 'fa-search',
                        'fa-check-circle', 'fa-exclamation-circle',
                        'fa-comment-alt','fa-hand-pointer', 'fa-clipboard-list',
                        'fa-quote-left', 'fa-quote-right','fa-bullhorn',
                        'fa-file-alt', 'fa-file-circle-question', 'fa-circle-info'
                    ];

    var iconWrapper = document.getElementById('icon-wrapper');
    var iconSearch = document.getElementById('icon-search');
    var iconInput = document.getElementById('icon-input');

    function getPrefix(iconClass) {
        return 'fas';
    }

    function filterIcons(searchText) {
        iconWrapper.innerHTML = '';
        iconlist.forEach(function(iconClass) {
            if (iconClass.toLowerCase().includes(searchText)) {
                const prefix = getPrefix(iconClass);
                const iconDiv = document.createElement('div');
                const iconElement = document.createElement('i');

                iconElement.classList.add(prefix, iconClass);
                iconElement.style.fontSize = '24px';

                iconDiv.classList.add('i-block');
                iconDiv.style.cursor = 'pointer';
                iconDiv.style.padding = '6px';
                iconDiv.style.borderRadius = '5px';
                iconDiv.style.display = 'flex';
                iconDiv.style.alignItems = 'center';
                iconDiv.style.justifyContent = 'center';
                iconDiv.style.width = '40px';
                iconDiv.style.height = '40px';

                if (iconInput.value.trim() === prefix + ' ' + iconClass) {
                    iconDiv.style.border = '2px solid #007bff';
                    iconDiv.style.backgroundColor = '#e7f1ff';
                }

                iconDiv.title = prefix + ' ' + iconClass;

                iconDiv.addEventListener('click', function() {
                    iconInput.value = prefix + ' ' + iconClass;
                    filterIcons(iconSearch.value.toLowerCase());
                });

                iconDiv.appendChild(iconElement);
                iconWrapper.appendChild(iconDiv);
            }
        });
    }

    iconSearch.addEventListener('keyup', function() {
        filterIcons(iconSearch.value.toLowerCase());
    });

    filterIcons('');
</script>
