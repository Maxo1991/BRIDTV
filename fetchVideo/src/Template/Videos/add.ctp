<?php
    $error = '';
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Get Video'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Videos'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Edit User details'), ['controller' => 'users', 'action' => 'edit', $this->Session->read('Auth.User.id')]) ?></li>
    </ul>
</nav>
<div class="videos form large-9 medium-8 columns content">
    <?= $this->Form->create($video, ['action' => 'add']) ?>
    <fieldset>
        <legend><?= __('Get Video') ?></legend>
        <?php
            echo $this->Form->control('yt_video_link', ['label' => 'Source']);
            echo $this->Form->control('title');
        ?>
    </fieldset>

    <?php if($error){?>
        <div class="text-danger"><b><?php echo $error?></b></div>
    <?php } ?>
    <?= $this->Form->end() ?>
    <button class="btn btn-warning" id="save" style="margin-left: 90%; margin-right: 10%;">Save</button>
</div>
<script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script>
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
    (function($){
        $(document).ready(function(){
            $('#save').click(function(){
                var source = $('#yt-video-link').val();
                var check = source.includes('https://www.youtube.com/watch?');
                if(source.includes('https://www.youtube.com/watch?v=')){
                    var code = source.substring(32, 43);
                    source = "https://www.youtube.com/watch?v=" + code;
                }
                if(source.includes('https://youtu.be')){
                    var result = source.split("/");
                    var lastElement = result.slice(-1)[0];
                    source = "https://www.youtube.com/watch?v=" + lastElement;
                }
                if(source.includes('<iframe')){
                    var position = source.indexOf('embed/') + 6;
                    var lastElement = source.substring(position, position + 11);
                    source = "https://www.youtube.com/watch?v=" + lastElement;
                }
                var title = $('#title').val();
                $.ajax({
                    url: '/fetchVideo/videos/ajax',
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': csrfToken
                    },
                    dataType: 'text',
                    data: {
                        source: source,
                        title: title
                    }, success: function (response) {
                        response = JSON.parse(response);
                        var format = response.videos.formats.length;
                        var formats = response.videos.formats;
                        var downloadDiv = "<div class='container mt-5'>";
                        if(format > 0){
                            downloadDiv += "<div class='row mt-4 text-center'>";
                            downloadDiv += "<div class='col-lg-12'>";
                            downloadDiv += "<img src='" + response.videos.thumbnail + "' class='img-responsive'>";
                            downloadDiv += "</div>";
                            downloadDiv += "<div class='col-lg-12'>";
                            downloadDiv += "<b>" + title + "</b>";
                            downloadDiv += "</div>";
                            downloadDiv += "</div>";
                            downloadDiv += "<div class='row mt-4'>";
                            downloadDiv += "<div class='col-lg-8 offset-2'>";
                            downloadDiv += "<table class='table table-bordered'>";
                            downloadDiv += "<tr>";
                            downloadDiv += "<th>Type</th>";
                            downloadDiv += "<th>Quality</th>";
                            downloadDiv += "<th>Size</th>";
                            downloadDiv += "<th>Download</th>";
                            downloadDiv += "</tr>";
                            formats.forEach(myFunction);
                            function myFunction(item){
                                if(item.size != 0){
                                    downloadDiv += "<tr>";
                                    downloadDiv += "<td>" + item.type + "</td>";
                                    downloadDiv += "<td>" + item.quality + "</td>";
                                    downloadDiv += "<td>" + parseFloat(item.size / 1048567).toFixed(2) + " MB</td>";
                                    downloadDiv += "<td><a href='/fetchVideo/videos/download?link=" + encodeURI(item.link) + "&title=" + encodeURI(response.videos.title) + "&type=" + encodeURI(item.type) + "&bitrate=" + item.bitrate + "&duration=" + response.videos.duration + "&website=" + response.videos.website + "&size=" + item.size + "' class='btn btn-primary'>Download</a> </td>";
                                    downloadDiv += "</tr>";
                                }
                            }
                            downloadDiv += "</table>";
                            downloadDiv += "</div>";
                            downloadDiv += "</div>";
                        }
                         downloadDiv += "</div>";
                        $('.videos').append(downloadDiv);
                    }
                });
                }
            )
        });
    })(jQuery);
</script>
