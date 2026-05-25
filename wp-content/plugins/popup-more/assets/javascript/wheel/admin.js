function YpmAdmin() {
    this.init();
}

YpmAdmin.prototype.init = function() {
    this.calcPercentage();
    this.proRedirect();
}

YpmAdmin.prototype.proRedirect = function() {
    jQuery('.disabled-button').on('click', function() {
        window.open(yrmWheelAdmin.proURL)
    })
}

YpmAdmin.prototype.calcPercentage = function() {
    jQuery(document).on('change', '.ypm-prize-probability', function(e) {
        e.preventDefault();

        var total = 0;
        jQuery('.ypm-prize-probability').each(function() {
            total += parseInt(jQuery(this).val()) || 0;
        });

        if (total > 100) {
            var excess = total - 100;
            var currentValue = parseInt(jQuery(this).val()) || 0;
            jQuery(this).val(Math.max(0, currentValue - excess));
        }
    });
}

jQuery(document).ready(function() {
    new YpmAdmin();
});
