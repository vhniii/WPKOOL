// algolplus

var slnMyAccount = {
    cancelBooking: function (id) {
        if (!confirm(salon.confirm_cancellation_text)) {
            return;
        }

        jQuery.ajax({
            url: salon.ajax_url,
            data: {
                action: 'salon',
                method: 'cancelBooking',
                id: id
            },
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (typeof data.redirect != 'undefined') {
                    window.location.href = data.redirect;
                } else if (data.success != 1) {
                    alert('error');
                    console.log(data);
                } else {
                    slnMyAccount.loadContent('cancelled');
                }
            },
            error: function(data){alert('error'); console.log(data);}
        });
    },

    loadContent: function (option) {
        jQuery.ajax({
            url: salon.ajax_url,
            data: {
                action: 'salon',
                method: 'myAccountDetails',
                option: option,
            },
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (typeof data.redirect != 'undefined') {
                    window.location.href = data.redirect;
                } else {
                    jQuery('#sln-salon-my-account-content').html(data.content);
                    sln_createRatings(true, 'circle');
                    jQuery("[data-toggle='tooltip']").tooltip();

                    if( slnMyAccount.feedback_id ) {
                        slnMyAccount.showRateForm( slnMyAccount.feedback_id  );
                    }
                    slnMyAccount.setActiveTab();
                    jQuery('.nav-tabs a').on('show.bs.tab', slnMyAccount.setActiveHash);
                    jQuery('#salon-my-account-profile-form input[name="action"]').val('salon');
                    jQuery('#salon-my-account-profile-form').submit(slnMyAccount.updateProfile);
                }
            },
            error: function(data){alert('error'); console.log(data);}
        });
    },

    loadNextHistoryPage: function () {
        var page = parseInt(jQuery('#sln-salon-my-account-history-content table tr:last').attr('data-page'))+1;
        jQuery.ajax({
            url: salon.ajax_url,
            data: {
                action: 'salon',
                method: 'myAccountDetails',
                args: {
                    part: 'history',
                    page: page,
                }
            },
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (typeof data.redirect != 'undefined') {
                    window.location.href = data.redirect;
                } else {
                    jQuery('#sln-salon-my-account-history-content').html(data.content);
                    if(jQuery('#sln-salon-my-account-history-content table tr:last').attr('data-end') == 1) {
                        jQuery('#next_history_page_btn').remove();
                    }
                    sln_createRatings(true, 'circle');
                    jQuery("[data-toggle='tooltip']").tooltip();
                }
            },
            error: function(data){alert('error'); console.log(data);}
        });
    },

    showRateForm: function (id) {
        sln_createRaty(jQuery("#ratingModal .rating"));
        jQuery("#ratingModal textarea").attr('id', id);
        jQuery("#ratingModal textarea").val('');

        jQuery("#ratingModal #step2").css('display', 'none');
        jQuery("#ratingModal").modal('show');
        jQuery("#ratingModal #step1").css('display', 'block');

        return false;
    },

    sendRate: function() {
        if (jQuery("#ratingModal .rating").raty('score') == undefined || jQuery("#ratingModal textarea").val() == '')
            return false;

        jQuery.ajax({
            url: salon.ajax_url,
            data: {
                action: 'salon',
                method: 'setBookingRating',
                id: jQuery("#ratingModal textarea").attr('id'),
                score: jQuery("#ratingModal .rating").raty('score'),
                comment: jQuery("#ratingModal textarea").val(),
            },
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (typeof data.redirect != 'undefined') {
                    window.location.href = data.redirect;
                } else if (data.success != 1) {
                    alert('error');
                    console.log(data);
                } else {
                    jQuery("#ratingModal #step1").css('display', 'none');
                    jQuery("#ratingModal #step2").css('display', 'block');

                    jQuery('#ratingModal .close').delay(2000).queue(function () {
                        jQuery(this).click();
                        slnMyAccount.loadContent();
                        jQuery(this).dequeue();
                    });

                    slnMyAccount.feedback_id = false;
                }
            },
            error: function(data){alert('error'); console.log(data);}
        });
        return false;
    },

    setActiveHash: function(e){
        window.location.hash = e.target.hash;
    },

    setActiveTab: function(hash){
        var hash = hash ? hash : window.location.hash;
        if(hash)
        jQuery('.nav-tabs a[href="' + hash + '"]').tab('show');
    },


    updateProfile: function(e){
        e.preventDefault();
        var form = e.target;
        var data = jQuery(form).serialize();
        var statusContainer = jQuery('#salon-my-account-profile-form .statusContainer');
        statusContainer.parent().hide();
        statusContainer.html('');
        data += "&method=UpdateProfile";
        //"&s=" + $('#sln-update-user-field').val() + "&security=" + salon.ajax_nonce;
        jQuery.ajax({
            url: salon.ajax_url,
            data: data,
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                statusContainer.parent().show();
                if(data.status === 'success'){
                    statusContainer.append('<div class="sln-alert">'+salonMyAccount_l10n.success+'</div>')
                }else{
                    data.errors.forEach(function(error){
                        statusContainer.append('<div class="sln-alert sln-alert--problem">'+ error +'</div>');
                    })
                }
                
            },
            error: function(data){alert('error'); console.log(data);}
        });
    },

    init: function () {
        if (jQuery('#sln-salon-my-account-content').size()) {
            this.loadContent();
        }
        else /*if (jQuery('[name=post_type]').val() == 'sln_booking')*/ {
            sln_createRatings(true, 'star');
        }
    }
};

function addClassIfNarrow(element, narrowClass) {
    if (element.length > 0) {
        jQuery(window).on("load resize",function(){
            var elementWidth = element.width();
            if (elementWidth < 769){
                element.addClass(narrowClass);
            } else {
                element.removeClass(narrowClass);
            }
        });
    }
}

jQuery(document).ready(function() {
    slnMyAccount.init();
    addClassIfNarrow(jQuery('#sln-salon-my-account'), 'mobile-version');
});
