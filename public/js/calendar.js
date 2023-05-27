class SOVACalendar
{
    #playlistId

    #calendar

    #yearContainer

    #monthContainer

    #daysContainer
    
    #monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec']

    #startDate = null

    #endDate = null


    constructor(playlistId) 
    {
        this.#playlistId = playlistId
        this.#calendar = 
            $(`#calendar-${playlistId}`)
        this.#yearContainer = 
            this.#calendar.find('.calendar__year')
        this.#monthContainer = 
            this.#calendar.find('.calendar__month')
        this.#daysContainer = 
            this.#calendar.find('.calendar__days')

        this.#ini()
    }

    #ini() 
    {
        this.#renderMonth(
            new Date().getFullYear(),
            new Date().getMonth()
        )
        setTimeout(() => {
            this.#calendar
                .find('.calendar-body__range').first()
                .trigger('click')
        }, 1000)
        
    }

    #renderMonth(year, month)
    {
        this.#yearContainer.text(year)
        this.#monthContainer.text(this.#monthNames[month])
    
        let days = ''

        // previous month days
        const lastDayPositionPrevMonth = 
            new Date(year, month, 0).getDay()
        if (lastDayPositionPrevMonth < 6) { // if last day isn't Sat
            for (let i = lastDayPositionPrevMonth; i >= 0; i--) {
                days += `<span class="prev-day">
                            ${new Date(year, month, 0).getDate() - i}
                        </span>`
            }
        }
        // current month days
        const daysCountCurrentMonth = 
            new Date(year, month + 1, 0).getDate()
        for (let i = 1; i <= daysCountCurrentMonth; i++) {
            let dayPosition = new Date(year, month, i).getDay()
            if (dayPosition == 0 || dayPosition == 6) { // if day off
                days += `<span class="curr-day day-off">${i}</span>`
            } else {
                days += `<span class="curr-day">${i}</span>`
            }
        }
        // next month days
        const lastDayPositionCurrentMonth = 
            new Date(year, month, daysCountCurrentMonth).getDay()
        for (let i = 1; i <= 6 - lastDayPositionCurrentMonth; i++) {
            days += `<span class="next-day">${i}</span>`
        }

        this.#daysContainer.html(days)
    }

    renderPrevMonth(e) {
        const currentDate = new Date(
            this.#yearContainer.text(),
            this.#monthNames.indexOf(
                this.#monthContainer.text()
            )
        )

        // previous month
        currentDate.setMonth(currentDate.getMonth() - 1)

        this.#renderMonth(
            currentDate.getFullYear(),
            currentDate.getMonth()
        )
    }

    renderNextMonth(e) {
        const currentDate = new Date(
            this.#yearContainer.text(),
            this.#monthNames.indexOf(
                this.#monthContainer.text()
            )
        )

        // next month
        currentDate.setMonth(currentDate.getMonth() + 1)

        this.#renderMonth(
            currentDate.getFullYear(),
            currentDate.getMonth()
        )
    }

    selectRange(e)
    {
        e.stopPropagation()
        this.#calendar
            .find('.calendar-body__range')
            .removeClass('selected')
        const selectedRange = $(e.currentTarget)
        selectedRange.addClass('selected')
        this.#calendar
            .find('.calendar-body__selected')
            .text(selectedRange.text())
        this.#startDate = selectedRange.attr('data-start')
        this.#endDate = selectedRange.attr('data-end')
    }

    setCustomRange(e) 
    {
        let selectedDays = 
            this.#calendar.find('.calendar__days .curr-day.range-edge')
        if (selectedDays.length == 2) { // if range exists
            this.#resetCustomRange()
        }
        $(e.currentTarget).addClass(['range', 'range-edge'])

        selectedDays = 
            this.#calendar.find('.calendar__days .curr-day.range-edge')
        if (selectedDays.length == 2) { // if range is set
            this.#renderCustomRange()
        }

        this.#setCustomRangeAttrs()
        this.#calendar
            .find('.calendar-body__range.custom')
            .trigger('click')
    }

    #setCustomRangeAttrs()
    {
        const rangeStart = 
            this.#calendar.find('.calendar__days .curr-day.range').first()
        const rangeEnd =
            this.#calendar.find('.calendar__days .curr-day.range').last()
        const customRange = 
            this.#calendar.find('.calendar-body__range.custom')
        customRange.attr(
            'data-start', 
            new Date(
                this.#yearContainer.text(),
                this.#monthNames.indexOf(
                    this.#monthContainer.text()
                ),
                rangeStart.text()
            ).getTime() / 1000
        )
        customRange.attr(
            'data-end', 
            new Date(
                this.#yearContainer.text(),
                this.#monthNames.indexOf(
                    this.#monthContainer.text()
                ),
                rangeEnd.text()
            ).getTime() / 1000
        )
    }

    #resetCustomRange()
    {
        const selectedDays = 
            this.#calendar.find('.calendar__days .curr-day.range')
        selectedDays.removeClass(
            [
                'range', 
                'range-edge', 
                'range-start', 
                'range-end',
            ]
        )
    }

    #renderCustomRange()
    {
        const rangeStart = 
            this.#calendar.find('.calendar__days .curr-day.range').first()
        const rangeEnd =
            this.#calendar.find('.calendar__days .curr-day.range').last()
        rangeStart.addClass('range-start')
        rangeEnd.addClass('range-end')
        const range = this.#calendar.find('.calendar__days span')
        range
            .slice(rangeStart.index(), rangeEnd.index())
            .addClass('range')
    }

    getData(e)
    {
        this.#calendar
            .find('.calendar-btn > span')
            .text(
                this.#calendar
                    .find('.calendar-body__range.selected')
                    .text()
            )

        const tracksContainer = 
            $(`#playlist-${this.#playlistId} .playlist__tracks`)
        tracksContainer.addClass('loading')

        $.ajax(ajaxUrl, {
            type: 'GET',
            data: {
                action: 'date_range_tracks',
                playlist_id: this.#playlistId,
                start: this.#startDate,
                end: this.#endDate
            },
            success: res => {
                tracksContainer.html(res.data)
                tracksContainer.removeClass('loading')
            },
            error: error => {
                tracksContainer.removeClass('loading')
            },
        })
    }

    show(e)
    {
        this.#calendar.addClass('show')
    }

    hide(e)
    {
        e.stopPropagation()
        this.#calendar.removeClass('show')
    }
}

function sovaCalendar(
    playlistId,
) {
    const calendar = new SOVACalendar(
        playlistId,
    )

    $(`#calendar-${playlistId} .calendar__prev`)
        .click(e => calendar.renderPrevMonth(e))
    $(`#calendar-${playlistId} .calendar__next`)
        .click(e => calendar.renderNextMonth(e))
    $(document).on(
        'click', 
        `#calendar-${playlistId} .calendar__days .curr-day`, 
        e => calendar.setCustomRange(e)
    )
    $(`#calendar-${playlistId} .calendar-body__range`)
        .click(e => calendar.selectRange(e))
    $(`#calendar-${playlistId} .calendar-body__btn-apply`)
        .click(e => calendar.getData(e))
    $(`#calendar-${playlistId} .calendar-btn`)
        .click(e => calendar.show(e))
    $(`#calendar-${playlistId} .calendar-body__btn-cancel`)
        .click(e => calendar.hide(e))
}