<div class="modal fade" id="assignProgramModal" tabindex="-1" role="dialog" aria-labelledby="assignProgramModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="assignProgramForm" method="POST" action="#">
                @csrf
                <input type="hidden" name="player_id" id="assign_player_id">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="la la-link mr-1"></i> {{ __('player.titles.assign_program') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>

                <div class="modal-body">
                    <div id="assignProgramErrors" class="alert alert-danger d-none"></div>

                    <div id="assignProgramLoader" class="text-center py-5">
                        <span class="spinner-border spinner-border-sm"></span> {{ __('player.actions.loading') }}
                    </div>

                    <div id="assignProgramContent" style="display: none">
                        <div class="form-group">
                            <label>{{ __('program.fields.program') }}</label>
                            <select name="program_id" id="assign_program_id" class="form-control">
                                <option value="" disabled selected>{{ __('player.actions.select') }}</option>
                            </select>
                            <div id="assign_program_details" class="text-muted small mt-2">
                                <div><strong>{{ __('program.fields.base_price') }}:</strong> <span
                                        id="price_base">0.00</span></div>
                                <div><strong>{{ __('program.fields.vat') }}:</strong> <span id="price_vat">0.00</span>
                                </div>
                                <div><strong>{{ __('program.fields.total') }}:</strong> <span
                                        id="price_total">0.00</span></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ __('class.titles.classes') }}</label>
                            <select name="class_ids[]" id="assign_class_ids" class="form-control" multiple></select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">
                        <i class="la la-check-circle"></i> {{ __('player.actions.assign') }}
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        {{ __('player.actions.cancel') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
