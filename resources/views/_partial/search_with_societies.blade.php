<form action="{{ route($filter_url) }}" method="get" class="loader">
    <div class="alert alert-light alert-elevate" role="alert">
        <div class="col-md-2"></div>
        <div class="alert-icon"><i class="flaticon-search kt-font-brand"></i></div>
        <div class="form-group validated col-sm-6 ">
            <label class="form-control-label"><b></b></label>
            <select class="form-control kt-selectpicker" name="society_id" data-live-search="true" required>
                <option selected disabled>  {{ __('Select Society')}}</option>
                <option {{ ($society_id == 'all') ? 'selected' : '' }} value="all">  {{ __('All Societies')}}</option>
                @forelse($societies as $soc)
                    <option {{ ($society_id == $soc->id) ? 'selected' : '' }} value="{{$soc->id}}">{{ $soc->name }}</option>  
                @empty
                    <option disabled>No Society Found</option>
                @endforelse
            </select>
        </div>
        <div class="kt-section__content kt-section__content--solid mt-4">
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
        </div>
    </div>
</form>