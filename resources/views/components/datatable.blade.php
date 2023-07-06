<div class="table-responsive table-container">
    <table class="display nowrap table table-hover table-striped table-bordered datatable"
           id="{{ $divId }}"
           cellspacing="0"
           width="100%"
           data-sort="{{ $sort }}"
           data-ajaxUrl="{{ $url }}"
           data-ajaxMethod="{{ $method }}"
           data-rowClick="{{ $rowClick }}"
           data-pageLength="{{ $pageLength }}"
           data-lengthChange="{{ $lengthChange }}"
           data-paginatiOnTop="{{ $paginatiOnTop }}"
    >
        <thead>
        {{ $slot }}
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
