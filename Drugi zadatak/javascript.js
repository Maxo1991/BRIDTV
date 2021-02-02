/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////KOMPLETAN ZADATAK DA SE NAPRAVI TABELA I DA SE DESAVAJU ODREDJENE PROMENE KADA SE KLIKNE NA POLJA U TABELI//////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
<!DOCTYPE HTML>
<html>
<head>
    <style>
        table tr td{padding: 20px; border: 1px solid gray; text-align: center;}
    </style>
</head>
<body>
<script>
    table = document.createElement("table");
    var c = 1;
    for( i = 1; i < 7; i++){
    tr = document.createElement("tr");
        for( j = 1; j < 7; j++){
            td = document.createElement("td");
            td.innerText = c;
            tr.appendChild(td);
            c++;
        }
        table.appendChild(tr);
    }
    document.body.appendChild(table);
    // console.log(tr);
</script>
<div id="vrednost" style="width: 100px; height: 100px; border: 1px solid gray; text-align: center; line-height: 100px;"></div>
<script>
    var td = document.getElementsByTagName("td");
    for (var i = 0; i < td.length; i++) {
    td[i].onclick = function(){
    if(this.style.backgroundColor === "red"){
    this.style.backgroundColor = "";
    document.getElementById("vrednost").innerText = "";
            }else{
                for(var k=0; k < td.length; k++) {
                    td[k].style.backgroundColor = "";
                }
                this.style.backgroundColor = "red";
                document.getElementById("vrednost").innerText = this.textContent;
            }
        };
    }
</script>
</body>
</html>


/////////////////////////////////////////////////////////////
///////////////EXPORT CSV FILE///////////////////////////////
/////////////////////////////////////////////////////////////
<script>
    var data = "data:text/csv;charset=utf-8,ID,NAME,COUNTRY CODE\n";
    $(document).ready(function () {
        exportToCSV(0, <?php echo $numRows ?>);
    });
    function exportToCSV(start, max) {
        if (start > max) {
            $("#response").html('<a href="'+data+'" download="countryTable.csv">Download</a>');
            return;
        }

        $.ajax({
            url: 'secondmethod.php',
            method: 'POST',
            dataType: 'json',
            data: {
                start: start
            },
            success: function (response) {
                data += response.data;
                exportToCSV((start + 50), max);
            }
        });
    }
    </script>

//////////////////////////////////////////////////////////////////////////////////////
/////////KADA SE UNOSI TEKST DA SE AUTOMATSKI PROVERAVAJU REZULTATI///////////////////
//////////////////////////////////////////////////////////////////////////////////////
               $("#searchBox").keyup(function () {
                    var query = $("#searchBox").val();

                    if (query.length > 0) {
                        $.ajax(
                            {
                                url: 'index.php',
                                method: 'POST',
                                data: {
                                    search: 1,
                                    q: query
                                },
                                success: function (data) {
                                    $("#response").html(data);
                                },
                                dataType: 'text'
                            }
                        );
                    }
                });

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////FUKCIJA KOJOM SE KUPE PODACI IZ FORME I PROSLEDJUJU AJAKSOM DA SE POSALJE EMAIL I VRACANJE ODGOVORA DA LI JE USPELO SLANJE//////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        function sendEmail(){
            var name = $('#name');
            var email = $('#email');
            var subject = $('#subject');
            var body = $('#body');

            if(isNotEmpty(name) && isNotEmpty(email) && isNotEmpty(subject) && isNotEmpty(body)){
                $.ajax({
                    url: "sendEmail.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        name: name.val(),
                        email: email.val(),
                        subject: subject.val(),
                        body: body.val()
                    },
                    success: function (response) {
                        if(response.status === "success"){
                            alert("Email has been sent!");
                        }else{
                            alert("Please try again!");
                            console.log(response);
                        }
                    }
                })
            }
        }

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////FUNKCIJE ZA KREIRANJE SLAJDERA////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        var stopSlideshow = false;
        function slideshow(caller) {
            var status = $(caller).attr('value');
            if (status.indexOf('Start') > -1) {
                stopSlideshow = false;
                $(caller).attr('value', 'Stop Slideshow');
                $(caller).addClass('btn-danger').removeClass('btn-success');
            } else {
                stopSlideshow = true;
                $(caller).attr('value', 'Start Slideshow');
                $(caller).addClass('btn-success').removeClass('btn-danger');
            }
            var interval = setInterval(function () {
                if (!stopSlideshow)
                    changeSlide('next');
                else
                    clearInterval(interval);
            }, 2000);
        }
        function changeSlide(direction) {
            var currentImg = $('.active');
            var nextImg = currentImg.next();
            var previousImg = currentImg.prev();
            if (direction == 'next') {
                if (nextImg.length)
                    nextImg.addClass('active');
                else
                    $('.slider img').first().addClass('active');
            } else {
                if (previousImg.length)
                    previousImg.addClass('active');
                else
                    $('.slider img').last().addClass('active');
            }
            currentImg.removeClass('active');
        }


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////PROMENA REDOSLEDA U TABELI, KADA SE PROMENI REDOSLED UZ POMOC AJAXA SE POZIVA FAJL U KOJEM SE MENJA REDOSLED U BAZI////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $(document).ready(function(){
            $("table tbody").sortable({
                update: function(event, ui){
                    $(this).children().each(function(index){
                        if($(this).attr('data-position') !== index + 1){
                            $(this).attr('data-position', (index + 1)).addClass('updated');
                        }
                    });
                    SaveNewPositions();
                }
            });
        })

        function SaveNewPositions(){
            var positions = [];
            $('.updated').each(function(){
                positions.push([$(this).attr('data-index'), $(this).attr('data-position')]);
                $(this).removeClass('updated');
            })
            $.ajax({
                url: 'index.php',
                method: 'POST',
                dataType: 'text',
                data: {
                    update: 1,
                    positions: positions
                },
                success: function(response){
                    console.log(response);
                }
            })
        }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////DA SE NA PROMENU PRVOG SELEKTA TJ. VOZILA PROMENE AUTOMATSKI VREDNOSTI U DRUGOM SELEKTU KOJI SU MODELI AUTOMOBILA(SVE IZ JSON FAJLA + JQUERY)///////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$.getJSON( 'automobili.json', function(result){
            $.each(result, function(i, automobili){
                $('#marka').append('<option value="' + automobili.brand + '">' + automobili.brand + '</option>');
            })
        })
        $('#marka').change(function(){
            var model = document.getElementById('model');
            $("#model").empty();
            $.getJSON( 'automobili.json', function(result){
                $.each(result, function(i, model){
                    var conceptName = $('#marka').find(":selected").text();
                    if(conceptName ==  model.brand) {
                        var modeli = model.models;
                        for (j = 0; j < modeli.length; j++) {
                            $('#model').append('<option value="' + modeli[j] + '">' + modeli[j] + '</option>');
                        }
                    }
                })
            })
        })

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////NA KLIK SE VRSE DELETE, UPDATE I EDIT AKCIJE(KADA IMA VISE ELEMENATA SA ISTOM KLASOM, A DA SE IZVRSAVA SAMO NA KLIKNUTOJ)///////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$(document).on('click', '#submit_btn', function(){
            var name = $('#name').val();
            var comment = $('#comment').val();
            $.ajax({
                url : 'server1.php',
                type: 'POST',
                data: {
                    'save' : 1,
                    'name' : name,
                    'comment' : comment
                },
                success : function(response){
                    $('#name').val('');
                    $('#comment').val('');
                    $('#display_area').append(response);
                }
            })
        })

        //delete comment
        $(document).on('click', '.delete', function(){
            var id = $(this).attr('data_id');
            $clicked_btn = $(this);
            $.ajax({
                url : 'server1.php',
                type: 'GET',
                data : {
                    'delete' : 1,
                    'id' : id
                },
                success: function(response){
                    $clicked_btn.parent().remove();
                    $('#name').val('');
                    $('#comment').val('');
                }
            });
        })

        var edit_id;
        var $edit_comment;
        //edit forma
        $(document).on('click', '.edit', function(){
            edit_id = $(this).attr('data_id');
            $edit_comment = $(this).parent();
            var name = $(this).siblings('.display_name').text();
            var comment = $(this).siblings('.comment_text').text();
            $('#name').val(name);
            $('#comment').val(comment);
            $('#submit_btn').hide();
            $('#update_btn').show();
        })

        //update komentara
        $(document).on('click', '#update_btn', function(){
            var id = edit_id;
            var name = $('#name').val();
            var comment = $('#comment').val();
            $.ajax({
                url : 'server1.php',
                type: 'POST',
                data : {
                    'update' : 1,
                    'id' : id,
                    'name' : name,
                    'comment' : comment
                },
                success: function(response){
                    $('#name').val('');
                    $('#comment').val('');
                    $('#submit_btn').show();
                    $('#update_btn').hide();
                    $edit_comment.replaceWith(response);
                }
            })
        })

