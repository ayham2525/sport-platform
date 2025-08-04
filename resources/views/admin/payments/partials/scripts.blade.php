<script>
    $(document).ready(function() {
        $('#system_id').change(function() {
            const systemId = $(this).val();
            $('#branch_id, #academy_id, #player_id').empty();
            if (systemId) {
                $.get(`/admin/get-branches-by-system/${systemId}`, function(data) {
                    $('#branch_id').append(`<option value="">{{ __('payment.filters.select_branch') }}</option>`);
                    data.forEach(branch => {
                        $('#branch_id').append(`<option value="${branch.id}">${branch.name}</option>`);
                    });
                });
            }
        });

        $('#branch_id').change(function() {
            const branchId = $(this).val();
            $('#academy_id, #player_id').empty();
            if (branchId) {
                $.get(`/admin/get-academies-by-branch/${branchId}`, function(data) {
                    $('#academy_id').append(`<option value="">{{ __('payment.filters.select_academy') }}</option>`);
                    data.forEach(academy => {
                        $('#academy_id').append(`<option value="${academy.id}">${academy.name_en}</option>`);
                    });
                });
            }
        });

        $('#academy_id, #category').change(function() {
            const academyId = $('#academy_id').val();
            const category = $('#category').val();
            const defaultOption = '<option value="">' + "{{ __('payment.filters.select') }}" + '</option>';
            $('#player_id, #program_id').empty().append(defaultOption);

            if (academyId) {
                $.get(`/admin/get-programs-by-academy/${academyId}`, function(data) {
                    data.forEach(program => {
                        $('#program_id').append(`<option value="${program.id}">${program.name_en}</option>`);
                    });
                });
            }

            if (academyId && (category === 'program' || category === 'uniform')) {
                $('#player-dev, #program-dev, #class-dev').fadeIn();
                $.get(`/admin/get-players-by-system/${academyId}`, function(data) {
                    $('#player_id').append(`<option value="">{{ __('payment.filters.select') }}</option>`);
                    data.forEach(player => {
                        $('#player_id').append(`<option value="${player.id}">${player.name}</option>`);
                    });
                });
            } else {
                $('#player-dev, #program-dev, #class-dev').fadeOut();
            }
        });

        $('input[name="base_price"], input[name="vat_percent"], input[name="paid_amount"]').on('input', function () {
            calculateTotals();
        });

        calculateTotals();
    });

    function calculateTotals() {
        const basePrice = parseFloat($('input[name="base_price"]').val()) || 0;
        const vatPercent = parseFloat($('input[name="vat_percent"]').val()) || 0;
        const paidAmount = parseFloat($('input[name="paid_amount"]').val()) || 0;

        const vatAmount = (basePrice * vatPercent) / 100;
        const totalPrice = basePrice + vatAmount;
        const remaining = totalPrice - paidAmount;

        $('input[name="total_price"]').val(totalPrice.toFixed(2));
        $('input[name="remaining_amount"]').val(remaining.toFixed(2));
    }
</script>
