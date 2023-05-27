class SOVAPlaylist
{
    #playlist

    #playNext

    constructor(playlistId, playNext)
    {
        this.#playlist = $(`#playlist-${playlistId}`)
        this.#playNext = playNext
    }

    playStopTrack(e) 
    {
        const track = $(e.currentTarget)
        const audio = track.find('.track__audio')[0]
        if (audio.paused) { // if track not playing
            if (
                track.hasClass('active') && 
                track.hasClass('stoped')
            ) { // if track is active but stopped
                track.removeClass('stoped')
                audio.play()
            } else { // if track is not active
                // reset active track
                this.#resetActiveTrack()
                track.addClass('active')
                audio.play()
            }
            track.find('.track__audio')
                .bind('timeupdate', (e) => this.showTrackProgress(e))
        } else { // if track playing
            track.addClass('stoped')
            audio.pause()
        }
    }

    // reset active track
    #resetActiveTrack() 
    {
        const track = this
            .#playlist
            .find('.track.active')
        if (track.length == 0) { 
            return
        }
        track.removeClass(['active', 'end', 'stoped',])
        const audio = track.find('.track__audio')[0]
        audio.pause()
        audio.currentTime = 0
    }

    // show track progress
    showTrackProgress(e) 
    {
        const audio = e.currentTarget
        const track = $(audio).closest('.track')
        const time = this.#formatTrackTime(audio.currentTime)
        // show current time
        track.find('.play__currenttime')
            .text(time)
        // show progress bar
        const percent = (audio.currentTime / audio.duration) * 100;
        track.find('.progress')
            .width(percent + '%')
        if (percent == 100) { // if track is over
            this.#endTrack(track)
        }
    }

    // get formated track time
    #formatTrackTime(totalSec) 
    {
        const min = Math.floor(totalSec / 60)
        const sec = Math.floor(totalSec % 60)

        return min + ':' + sec.toString().padStart(2, '0')
    }

    // end track
    #endTrack(track) 
    {
        if (this.#playNext) { // playing one by one
            // reset active track
            this.#resetActiveTrack()
            // play next track
            let nextTrack = track.next()
            if (nextTrack.length == 0) { // if playlist is over
                nextTrack = $(this.#playlist.find('.track')[0])
            }
            const nextAudio = nextTrack.find('.track__audio')[0]
            nextTrack.addClass('active')
            nextAudio.play()
            nextTrack.find('.track__audio')
                .bind('timeupdate', (e) => this.showTrackProgress(e))
        } else { // end track
            track.addClass('end')
        }
    }

    // change volume
    changeVolume(e) {
        // new volume
        const volume = $(e.currentTarget).val()
        this.#playlist
            .find('.track')
            .each((i, track) => {
                // change input
                const input = $(track).find('input[type=range]')
                input.val(volume)
                // change audio volume
                const audio = $(track).find('.track__audio')[0]
                audio.volume = volume / 10
                
                if (audio.volume == 0) { // if volume is 0
                    $(track).find('.play__volume')
                        .addClass('off')
                } else {
                    $(track).find('.play__volume')
                        .removeClass('off')
                }
            })
    }

    // turn on/off volume
    turnOnOffVolume(e) {
        e.stopPropagation()
        // turn on or off
        const volumeOn = $(e.currentTarget)
            .closest('.play__volume')
            .hasClass('off')
        this.#playlist
            .find('.track')
            .each((i, track) => {
                const audio = $(track).find('.track__audio')[0]
                const input = $(track).find('input[type=range]')
                const volumeSection = $(track).find('.play__volume')
                if (volumeOn) { // turn on
                    audio.volume = 1
                    input.val(10)
                    volumeSection.removeClass('off')
                } else { // turn off
                    audio.volume = 0
                    input.val(0)
                    volumeSection.addClass('off')
                }
            })
    }

    // like track
    likeTrack(e) {
        e.stopPropagation()
        const likeSection = $(e.currentTarget).parent()
        if (likeSection.hasClass('liked')) { // if liked
            return
        } else {
            likeSection.addClass('liked')
        }
        const likeCount = likeSection.find('.count')
        const newCount = parseInt(likeCount.text()) + 1
        likeCount.text(newCount)
        this.#updateLikeCount(
            likeSection
                .closest('.track')
                .attr('data-track-id'),
        )
    }

    // send request to update like count
    #updateLikeCount(trackId) {
        $.get(ajaxUrl, {
            action: 'update_like_count',
            playlist_id: this.#playlist
                .attr('data-playlist-id'),
            track_id: trackId,
        })
    }

    // rewind track
    rewindTrack(e) 
    {
        e.stopPropagation()
        const progressBar = $(e.currentTarget)
        // progress bar width
        const width = progressBar.width()
        // cursor offset
        const clickX = e.offsetX
        const audio = progressBar
            .closest('.track')
            .find('.track__audio')[0]
        // rewind time
        const rewindTime = audio.duration * 
            (clickX / width)
        // set rewind time as current
        audio.currentTime = rewindTime
    }

    // show rewind progress
    showRewindProgress(e) 
    {
        const progressBar = $(e.currentTarget)
        // progress bar width
        const width = progressBar.width()
         // cursor offset
        const moveX = e.offsetX
        // progress
        const progressWidth = (moveX / width) * 100
        progressBar
            .find('.rewind-progress')
            .width(progressWidth + '%')
    }

    // reset rewind progress
    resetRewindProgress(e) 
    {
        $(e.currentTarget)
            .find('.rewind-progress')
            .width(0)
    }
}

// playlist registration
function sovaPlaylist(
    config
) {
    // playlist
    const playlist = new SOVAPlaylist(
        config.playlistId, 
        config.playNext
    )

    // playlist calendar
    if (config.calendar) sovaCalendar(config.playlistId)

    // event handlers
    $(document).on(
        'click', 
        `#playlist-${config.playlistId} .track`, 
        (e) => playlist.playStopTrack(e)
    )
    $(document).on(
        'click', 
        `#playlist-${config.playlistId} .play__volume input[type=range]`, 
        (e) => e.stopPropagation()
    )
    $(document).on(
        'input', 
        `#playlist-${config.playlistId} .play__volume input[type=range]`, 
        (e) => playlist.changeVolume(e)
    )
    $(document).on(
        'click', 
        `#playlist-${config.playlistId} .play__volume img`,
        (e) => playlist.turnOnOffVolume(e)
    )
    $(document).on(
        'click', 
        `#playlist-${config.playlistId} .track__like img`,
        (e) => playlist.likeTrack(e)
    )
    $(document).on(
        'click', 
        `#playlist-${config.playlistId} .play__progressbar`,
        (e) => playlist.rewindTrack(e)
    )
    $(document).on(
        'mousemove', 
        `#playlist-${config.playlistId} .play__progressbar`,
        (e) => playlist.showRewindProgress(e)
    )
    $(document).on(
        'mouseout', 
        `#playlist-${config.playlistId} .play__progressbar`,
        (e) => playlist.resetRewindProgress(e)
    )
}