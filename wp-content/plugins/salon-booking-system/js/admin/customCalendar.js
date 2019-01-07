jQuery(function($){
    initSalonCalendarUserSelect2($);
});

function calendar_getHourFunc() {
    return function (hour, part) {
        var time_start = this.options.time_start.split(":");
        var time_split = parseInt(this.options.time_split);
        var h = "" + (parseInt(time_start[0]) + hour * Math.max(time_split / 60, 1));
        var m = "" + (time_split * part + parseInt((hour == 0) ? parseInt(time_start[1]) : 0));
        var d = new Date();
        d.setHours(h)
        d.setMinutes(m);
        return moment(d).format(calendarGetTimeFormat());
    }
}

function calendar_getTimeFunc() {
    return function (part) {
        var time_start = this.options.time_start.split(":");
        var time_split = parseInt(this.options.time_split);
        var h = "" + ( parseInt(time_start[0]) );
        var m = "" + ( parseInt(time_start[1]) + time_split * part );
        var d = new Date();
        d.setHours(h)
        d.setMinutes(m);
        return moment(d).format(calendarGetTimeFormat());
    }
}

function calendar_getTransFunc() {
    return function (label) {
        return calendar_translations[label];
    }
}

function calendarGetTimeFormat() {
    // http://momentjs.com/docs/#/displaying/format/
    // vs http://www.malot.fr/bootstrap-datetimepicker/#options
    if (!salon.moment_time_format)
        salon.moment_time_format = salon.time_format
            .replace('ii', 'mm')
            .replace('hh', '{|}')
            .replace('H', 'h')
            .replace('{|}', 'H')
            .replace('p', 'a')
            .replace('P', 'A')
        ;
    return salon.moment_time_format;
}

function initSalonCalendar($, ajaxUrl, ajaxDay, templatesUrl,defaultView,firstDay) {
var DayCalendarHolydays = {
    "createButton":false,
    "selection":[],
    "blocked":false,    
    "rules":false,
    "selecting":false,
    "startEl":false,
    "mousedown": function(e){    
        if(!$(e.target).hasClass('cal-day-hour-part'))return;
        DayCalendarHolydays.clearSelection();        
        DayCalendarHolydays.selectEl($(this));        
        //$(' .cal-day-hour-part').on('mouseover', DayCalendarHolydays.mouseover);
        //$('body').on('mouseover', DayCalendarHolydays.bodyBlock);
    },
    "bodyBlock":function(e){
        var target = $(e.target);
        if(!(target.hasClass('cal-day-panel') || target.parents('#cal-day-panel').length))
        
        {
            DayCalendarHolydays.blocked = true;
            var event = jQuery.Event('click');
            event.target = $('body').find('.cal-day-hour-part:not(.blocked)')[0];
            $('body').trigger(event);
            return false;
        }
    },
    "mouseup": function(e){
        DayCalendarHolydays.selecting=false;  
        //$(' .cal-day-hour-part').off('mouseover', DayCalendarHolydays.mouseover);
        //$('body').off('mouseover', DayCalendarHolydays.bodyBlock);

        //console.log(DayCalendarHolydays.selection);
        //var filtered = Object.keys(DayCalendarHolydays.selection)
        //.map(function(x){ return parseInt(x) }),   

        var firstEl = DayCalendarHolydays.startEl,
        lastEl = $(e);
        var firstI =firstEl.index(), lastI = lastEl.index(),selected ;
        if( parseInt(firstI) > parseInt(lastI)  ){
            var temp = firstEl;
            firstEl = lastEl;
            lastEl = temp;
        }
        selected = parseInt(firstI) === parseInt(lastI) ? lastEl : firstEl.nextUntil(lastEl).add(firstEl).add(lastEl);
        selected.each(function(){
            $(this).addClass('selected');
            DayCalendarHolydays.selection[parseInt($(this).index())]= $(this);
        });
        
        var button = DayCalendarHolydays.createPopUp(1,firstEl,lastEl,DayCalendarHolydays.selection);
        button.click(DayCalendarHolydays.blockSelection)
        setTimeout(function(){$(' .cal-day-hour-part.selected').on('click', DayCalendarHolydays.clearSelection)},0);
        $(document).on('click', DayCalendarHolydays.clickOutside);
    },
    "mouseover": function(e){
        if(DayCalendarHolydays.blocked) return
        if($(this).hasClass("blocked")) 
            {
                DayCalendarHolydays.blocked = true;
                var event = jQuery.Event('click');
            event.target = $('body').find('.cal-day-hour-part:not(.blocked)')[0];
            $('body').trigger(event);
                return false;
            }
        else DayCalendarHolydays.selectEl($(this));        
    },
    "selectEl": function ($el){
        $el.addClass('selected');
        this.selection[parseInt($el.index())]= $el;
    },
    "click": function(e){
        if(!$(e.target).hasClass('cal-day-hour-part'))return;
        var attr = $(e.target).attr('data-action');
        if($(e.target).hasClass('block_date') || typeof attr !== typeof undefined && attr !== false)return;
        $('.cal-day-hour-part').removeClass('active')
        if(DayCalendarHolydays.selecting){
            DayCalendarHolydays.mouseup(e.target);
        }else{
            $(e.target).addClass('active');
        }
    },
    "startSelection":function(e){
         DayCalendarHolydays.clearSelection();        
         DayCalendarHolydays.startEl = $(e.target).closest('.cal-day-hour-part');
        DayCalendarHolydays.selectEl($(e.target).closest('.cal-day-hour-part'));    
        DayCalendarHolydays.selecting=true;    
        //$(' .cal-day-hour-part').on('mouseover', DayCalendarHolydays.mouseover);
        //$('body').on('mouseover', DayCalendarHolydays.bodyBlock);
    },
    "clearSelection":function(){
        if(DayCalendarHolydays.selection.length){
            if(DayCalendarHolydays.createButton && DayCalendarHolydays.createButton.hasClass('create-holydays'))DayCalendarHolydays.createButton.remove();
            DayCalendarHolydays.selection.forEach(function(e){ e.removeClass('selected') })
            DayCalendarHolydays.blocked = false;
        }
        DayCalendarHolydays.selection = [];
        $(' .cal-day-hour-part.selected').off('click', DayCalendarHolydays.clearSelection);
        $(document).off('click', DayCalendarHolydays.clickOutside);
    },
    "clickOutside":function(e){
        if(!$(e.target).closest('#cal-day-panel').length) {
            DayCalendarHolydays.clearSelection();
        }
    },
    "createPopUp":function (status,firstEl,lastEl,els){
        var firstB = firstEl.children('button[data-action="add-event-by-date"]');
        var lastB = lastEl.children('button[data-action="add-event-by-date"]');
        var firstD = firstB.attr('data-event-date'),
            firstT = firstB.attr('data-event-time'),
            lastD = lastB .attr('data-event-date'),
            final = lastEl.next().length ? lastEl.next().children('button[data-action="add-event-by-date"]') : lastB,
            lastT =  final.attr('data-event-time');
        var single = firstD+firstT === lastD+lastB.attr('data-event-time');

        var top = single ? (firstEl.position().top + ( firstEl.height() /2)) : firstEl.position().top+ (((lastEl.position().top + lastEl.height() ) - firstEl.position().top)/2) ;    
        var button = $('<button class=" '+( status ? ' create-holydays ': ' remove-holydays ')+' calendar-holydays-button"></button>');
        button.text((status ?  holidays_rules_locale.block :  holidays_rules_locale.unblock)+' '+(single? holidays_rules_locale.single: holidays_rules_locale.multiple));
        button.css({
            top: top,
            position:"absolute"
        });
        if(single) button.addClass('onlyone');
        button.appendTo(document.getElementById('cal-day-panel'));
        
        var selection = {
            'from_date' : firstD,
            'from_time' : firstT,
            'to_date' : lastD,
            'to_time' : lastT
        }    
        button.data('selection',selection );
        button.data('els',els );
        this.selection.data = selection
        this.createButton = button;
        return button;
    },
    "unblockPop": function(e){
        var target = $(this);
        DayCalendarHolydays.callAjax('Remove',function(data){
            if(data.rules === undefined) return;
            DayCalendarHolydays.rules= data.rules;       
            var els = target.data().els;        
            Object.keys(els).forEach(function(key){ $(els[key]).removeClass("blocked") })
            target.remove()
        },target.data().selection);
    },
    "blockSelection":function(){
        DayCalendarHolydays.callAjax('Add',function(data){
            if(data.rules === undefined) return;
            DayCalendarHolydays.rules= data.rules;
            DayCalendarHolydays.selection.forEach(function(e){ e.addClass("blocked").removeClass('selected') })
            var button = DayCalendarHolydays.createButton;
            DayCalendarHolydays.createButton = false;
            button.toggleClass('create-holydays remove-holydays')
            .text(holidays_rules_locale.unblock+' '+(DayCalendarHolydays.selection.length > 1 ? holidays_rules_locale.single: holidays_rules_locale.multiple))
            .off('click')
            .click(DayCalendarHolydays.unblockPop);
        })   
    },
    "callAjax": function(action,cb,target){        
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'salon',
                method: action+'HolydayRule',
                rule: target ? target : DayCalendarHolydays.selection.data,                
            },
            cache: false,
            dataType: 'json',
            success: cb
        });
    },
    "showRules":function(calendar){
        var p_rules = window.daily_rules;
        if(!DayCalendarHolydays.rules) DayCalendarHolydays.rules =  Object.keys(p_rules).map(function (key) { return p_rules[key]; });

        
        var rules = DayCalendarHolydays.rules.filter(function(e){
            return !!e && e.to_date === calendar.options.day
        });
        rules.forEach(function(rule){
            if(rule.from_time === '') rule.from_time = "9:00";
            var firstEl = $('button[data-event-time="'+rule.from_time+'"]').parent(),            
            lastEl = $('button[data-event-time="'+rule.to_time+'"]').parent(),            
            els = firstEl.add(firstEl.nextUntil(lastEl));
            els.addClass("blocked")
            var button = DayCalendarHolydays.createPopUp(0,firstEl,lastEl.prev(),els);
            button.off('click')
            .click(DayCalendarHolydays.unblockPop);
        })
    }    
};
    
    var options = {
		time_start:         $('#calendar').data('timestart'),
		time_end:           $('#calendar').data('timeend'),
		time_split:         $('#calendar').data('timesplit'),
	    first_day: firstDay,
        events_source: ajaxUrl,
        view: defaultView,
        tmpl_path: templatesUrl,
        tmpl_cache: false,
        format12: true,
        day: ajaxDay,
        onAfterEventsLoad: function (events) {
            if (!events) {
                return;
            }
            var list = $('#eventlist');
            list.html('');
            $.each(events, function (key, val) {
                $(document.createElement('li'))
                    .html(val.event_html)
                    .appendTo(list);
            });
        },
        onAfterViewLoad: function (view) {
            $('.current-view--title').text(this.getTitle());
            $('.btn-group button').removeClass('active');
            $('button[data-calendar-view="' + view + '"]').addClass('active');
            function today(){
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; //January is 0!
                var yyyy = today.getFullYear();

                if(dd<10) {
                    dd = '0'+dd
                } 

                if(mm<10) {
                    mm = '0'+mm
                } 

                today = yyyy + '-' + mm + '-' + dd ;
                return today;
            }
            var today = formatted_to_date(today());
            function formatted_to_date(fdate){
                var parts = fdate.split("-");
                return new Date(parts[0], parts[1] - 1, parts[2]);
            }
            $.each(sln_stats, function (key, val) {
                var calbar = $('.calbar[data-day="' + key + '"]');
                var append = '';
                var passed = formatted_to_date(key)<today;
                if (val.busy > 0) {
                    append += '<span class="'+ (passed ? 'passed' :'busy')+'" style="width: ' + val.busy + '%"></span>';
                }
                if (val.free > 0) {
                    append += '<span class="'+ (passed ? 'passed' :'free')+'" style="width: ' + val.free + '%"></span>';
                }
                calbar.attr('data-original-title', val.text).html(append);

            });
            if(view === 'day') DayCalendarHolydays.showRules(this);
        },
        classes: {
            months: {
                general: 'label'
            }
        },
        cal_day_pagination: '<button type="button" class="btn %class" data-page="%page"></button>',
        on_page: 11,
        _page: 0,
    };
    initDatepickers($);
    // CALENDAR
    //$('.cal-month-day.cal-day-inmonth [data-toggle="tooltip"]').click(function(e) {
    $(document).on("click", ".cal-month-day.cal-day-inmonth span", function (e) {
        e.preventDefault();
        $('.tooltip').hide(); 
        
    });

    var calendar = $('#calendar').calendar(options);
    $('.btn-group button[data-calendar-nav]').each(function () {
        var $this = $(this);
        $this.click(function () {
            calendar.navigate($this.data('calendar-nav'));
        });
    });

    $('.btn-group button[data-calendar-view]').each(function () {
        var $this = $(this);
        $this.click(function () {
            calendar.view($this.data('calendar-view'));
        });
    });

    $('#sln-calendar-user-field').change(function() {
        calendar.options._customer = parseInt($(this).val());
        calendar._render();
        calendar.options.onAfterViewLoad.call(calendar, calendar.options.view);
    });
    $('#sln-calendar-services-field').change(function() {
        var _events = $(this).val();
        if (Array.isArray(_events)) {
            _events = _events.map(parseInt);
        }
        else {
            _events = [];
        }

        calendar.options._services = _events;
        calendar._render();
        calendar.options.onAfterViewLoad.call(calendar, calendar.options.view);
    });

    $('#sln-calendar-assistants-mode-switch').change(function() {
        calendar.options._assistants_mode = $(this).is(':checked');
        calendar._render();
        calendar.options.onAfterViewLoad.call(calendar, calendar.options.view);
    });

    calendar.setLanguage($('html').attr('lang'));
    calendar.view();

    /*
     $('#first_day').change(function(){
     var value = $(this).val();
     value = value.length ? parseInt(value) : null;
     calendar.setOptions({first_day: value});
     calendar.view();
     });

     $('#language').change(function(){
     calendar.setLanguage($(this).val());
     calendar.view();
     });

     $('#events-in-modal').change(function(){
     var val = $(this).is(':checked') ? $(this).val() : null;
     calendar.setOptions({modal: val});
     });
     $('#events-modal .modal-header, #events-modal .modal-footer').click(function(e){
     //e.preventDefault();
     //e.stopPropagation();
     });
     */
    $('body')
    .on('click',' .cal-day-hour-part:not(.blocked)',DayCalendarHolydays.click);
    $('body')
    .on('click',' .block_date',DayCalendarHolydays.startSelection);
 
}

function initSalonCalendarUserSelect2($) {
    $('#sln-calendar-user-field').select2({
        allowClear: true,
        containerCssClass: 'sln-select-rendered',
        dropdownCssClass: 'sln-select-dropdown',
        theme: "sln",
        width: '100%',
        placeholder: $('#sln-calendar-user-field').data('placeholder'),
        language: {
            noResults: function () {
                return $('#sln-calendar-user-field').data('nomatches');
            }
        },
        ajax: {
            url: salon.ajax_url + '&action=salon&method=SearchUser&security=' + salon.ajax_nonce,
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    s: params.term
                };
            },
            minimumInputLength: 3,
            processResults: function (data, page) {
                return {
                    results: data.result
                };
            },
        }
    });
}
