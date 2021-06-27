<?php
    session_start();
    if(!isset($_SESSION['Authenticated'])||!$_SESSION['Authenticated']){
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="annoymous">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="homepage.css">
        <title>DB HW2</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.1/jquery.twbsPagination.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    </head>
    <body>
        
        <nav class="navbar navbar-expand-lg navbar-light navbar-laravel" style = "border-bottom : 2px solid rgba(235, 231, 231, 0.842); background-color:rgb(236, 236, 236);">
            <div class="container" id = "top-navigation" style = "margin-bottom:0px;" >
                <a class="navbar-brand" href="#"><h3>NMSL口罩訂購網</h3></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="home_page.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Check_exist_shop.php">Shop</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Page_personal_order.php">My Order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Page_shop_order.php">Shop Order</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="Logout.php">LogOut</a>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>
        <div class="container" style = " margin-top:40px">
            <div class="card bg-light mb-6" style="max-width:48rem;">
                <div class="card-header"><h4>User Profile</h4></div>
                <table class="table table-striped custab" style="margin-bottom:0;">
                    <?php
                        $dbservername='localhost';
                        $dbname='Mask_system';
                        $dbusername='examdb';
                        $dbpassword='examdb';
                        $uname = $_SESSION['Username'];
                        $conn = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
                        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $prereq = $conn->prepare("select * from Users where username = :username");
                        $prereq->execute(array("username"=>$uname));
                        echo("<tr ><td colspan=2>Profile</td></tr>");
                        foreach($prereq as $result){
                            echo ("<tr><td>Account</td><td>".$result['username']."</td></tr>");
                            echo ("<tr><td>Phone</td><td>".$result['phone_number']."</td></tr>");
                        }
                    ?>
                </table>
            </div>
        </div>
        <div class = "container">
            <div class="card bg-light mb-6" style="max-width:48rem;">
            <div class="card-header"><h4>Searching</h4></div>
            <div class="card-body">
            <script>
            var jsArray;
            var mode_price = 1, mode_amount=1;
            var formData_for_search;
            function sortjsArray(idx,mode){
                console.log("before:",jsArray);
                console.log(idx);
                jsArray.sort(
                    function(a,b){
                        return ((mode)?(parseInt(a[idx])-parseInt(b[idx])):(parseInt(b[idx])-parseInt(a[idx])));;
                    }
                );
            }
            function draw(cnt){
                $('#show').append(`<tr>
                                    <td>${jsArray[cnt]['store_name']}</td>
                                    <td>${jsArray[cnt]['city']}</td>
                                    <td>${jsArray[cnt]['price']}</td>
                                    <td>${jsArray[cnt]['number']}</td>
                                    <td>
                                            <form  method="post" class="create_order">
                                                <input type="number" name="order_mask_amount" required max=2147483647 min=0></input>
                                                <input type="hidden" name="row_number" value=${cnt}></input>
                                                <input type="hidden" name="store_name" value='${jsArray[cnt]['store_name']}'></input>
                                                <input type="hidden" name="mask_price" value = ${jsArray[cnt]['price']}></input>
                                                <input type="hidden" name="store_mask_amount" value=${jsArray[cnt]['number']}></input>
                                                <input type="submit" value="shop!"></input>
                                            </form>
                                    </td>
                                    </tr>`);
            }
            function generate_pagination(){
                let data_length = jsArray.length; 
                let per_page = 5;
                let total = Math.ceil(data_length/5);
                console.log("total_number:",total);   
                
                $('#show tbody').empty();
                if(total===0){
                    $('#not_found').html("<span id = \"span_error_msg\ class=\"text-danger\">cannot found the matching result</span>");
                    $('#not_found').show();
                }
                else{
                    console.log("trigger");
                    $('#not_found').hide();
                    $('#show').html(`<thead>
                                        <tr>
                                            <td>Shop</td><td>City</td>
                                            <td class = \"sorting_button\" id="price">Mask Price</td>
                                            <td class = \"sorting_button\" id="amount">Mask Amount</td>
                                        </tr>
                                    </thead>`);
                }
                
                if($('.pagination').data("twbs-pagination")){
                    $('.pagination').twbsPagination('destroy');
                }
                if(total==1){
                    $('#show').append("<tbody>");
                    for(let cnt = 0;cnt < data_length ; cnt++ ){
                        console.log('cnt',cnt);
                        draw(cnt);
                                    console.log(jsArray[cnt]['store_name']);
                    }
                    
                    $('#show').append("</tbody>");
                }
                else if(total!=0){
                    window.pagObj = $('#pagination').twbsPagination({
                    // totalPages如果妳一頁最多顯示2筆資料,那總長度就是除2 所以有5個分頁
                    totalPages: total,
                    visiblePages: 5,
                    onPageClick: function (event, page) {
                        var tableHeaderRowCount = 1;
                        var table = document.getElementById('show');
                        var rowCount = table.rows.length;
                        for (var i = tableHeaderRowCount; i < rowCount; i++) {
                            table.deleteRow(tableHeaderRowCount);
                        }
                        $('#show').append("<tbody>");
                        for(let cnt = (page-1)*per_page , idx = 0;  idx<per_page && cnt < data_length ; idx++,cnt++ ){
                            console.log('cnt',cnt);
                            draw(cnt);
                        }
                        $('#show').append("</tbody>");
                        $('#show').hide();
                        $('#show').fadeIn();
                        }
                        }).on('page', function (event, page) {
                            console.info(page + ' (from event listening)');
                        });
                }
                //$('#show').fadeIn();
            }
            function fetch_searching_result(){
                $.ajax({
                    type: "POST",
                    url: "search_shop.php",
                    data: formData_for_search, // serializes the form's elements.
                    success: function(data)
                    {  
                        jsArray= JSON.parse(data);
                        console.log(jsArray);
                        generate_pagination();
                    }
                });
            }
            function sortTable(idx,mode) {
                    var x = document.getElementsByClassName('sorting_button');
                    x[idx-2].setAttribute("onclick" ,`sortTable(${idx},${!mode})`);
                    var table, rows, switching, i, x, y, shouldSwitch;
                    table = document.getElementById("show");
                    switching = true;
                    /*Make a loop that will continue until
                    no switching has been done:*/
                    while (switching) {
                        //start by saying: no switching is done:
                        switching = false;
                        rows = table.rows;
                        for (i = 1; i < (rows.length - 1); i++) {
                        //start by saying there should be no switching:
                        shouldSwitch = false;
                        /*Get the two elements you want to compare,
                        one from current row and one from the next:*/
                        x = rows[i].getElementsByTagName("TD")[idx];
                        y = rows[i + 1].getElementsByTagName("TD")[idx];
                        //check if the two rows should switch place:
                        if (mode==1&&x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                        if (mode==0&&x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                        if (shouldSwitch) {
                        /*If a switch has been marked, make the switch
                        and mark that a switch has been done:*/
                            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                            switching = true;
                        }
                    }
                }
                $.fn.serializeObject = function() {
                    var o = {};
                    var a = this.serializeArray();
                    console.log(a);
                    $.each(a, function() {
                       if (o[this.name]) {
                            if (!o[this.name].push) {
                                o[this.name] = [o[this.name]];
                            }
                            o[this.name].push(this.value || '');
                        } else {
                            o[this.name] = this.value || '';
                        }
                        o[this.name]=this.value;
                    });
                    console.log(o);
                    return o;
                };

                                
                $(document).ready(function() {
                    $("#show_result").hide();
                    $(document).on('click','.sorting_button',function(event) {

                        console.log(event.target.id);
                        if(event.target.id == "price"){
                            sortjsArray(3,mode_price);
                            mode_price=!mode_price;
                        }
                        else if(event.target.id == "amount"){
                            sortjsArray(4,mode_amount);
                            mode_amount = !mode_amount;
                        }
                            
                        generate_pagination();
                    });
                    $('form.login').on('submit', function(e) {
                        e.preventDefault();
                        formData_for_search = $(this).serializeObject();
                        $('#show_result').fadeIn();
                        $.ajax({
                            type: "POST",
                            url: "search_shop.php",
                            data: formData_for_search, // serializes the form's elements.
                            success: function(data)
                            {  
                                jsArray= JSON.parse(data);
                                console.log(jsArray);
                                generate_pagination();
                            }
                        });
                    });
                    $(document).on('submit','form.create_order',function(e){
                        e.preventDefault();
                        console.log("trigger");
                        console.log($(this).serializeObject());
                        $.ajax({
                            type: "POST",
                            url: "handle_order.php",
                            data: $(this).serializeObject(), // serializes the form's elements.
                            success: function(data)
                            {  
                                console.log(data);
                                var tmp_arr = JSON.parse(data);
                                //alert(tmp_arr['message']);
                                $('#myModal .modal-body').html(`<p>${tmp_arr['message']}</p>`);
                                $('#myModal').modal('show');
                                //console.log(tmp_arr['value']);
                                fetch_searching_result();
                                
                            }
                        });
                    });
                });
            </script>
            <form class="login" onsubmit="">
                <div class="form-group row">
                    <label for="shopname" class="col-sm-2 col-form-label">Name</label>
                    <input type = "text" name = "shopname"></input>
                </div>
                <div class="form-group row">
                    <label for="city" class="col-sm-2 col-form-label">City</label>
                    <select name="city" id="form_City">
                            <option value="All">ALL</option>
                            <optgroup label="North area">
                                <option value="Taipei">Taipei</option>
                                <option value="Hsinchu">Hsinchu</option>
                            </optgroup>
                            <optgroup label="Central area">
                                <option value="Taichung">Taichung</option>
                                <option value="Chiayi">Chiayi</option>
                            </optgroup>
                            <optgroup label="South area">
                                <option value="Tainan">Tainan</option>
                                <option value="Kaohsiung">Kaohsiung</option>
                            </optgroup>
                    </select>
                </div>
                <div class="form-group row">
                    <label for="price_minimum" class="col-sm-2 col-form-label">Price</label>
                    <input style="width:30% " class="form-control" placeholder="minimum"  name="price_minimum" type="number"  min = 0 >
                    <div class="input-group-prepend">
                        <div class="input-group-text">~</div>
                    </div>
                    <input style="width:30% " class="form-control " placeholder="maximum"  name="price_maximum" type="number" min = 0 >
                    <!--<input type="number" name = "price_minimum"></input><span>~</span><input type = "number" name = "price_maximum"></input><br>-->
                </div>
                <div class="form-group row">
                    <label for="amount" class="col-sm-2 col-form-label">Amount</label>
                    <select name="amount">
                            <option value="all">All</option>
                            <option value="sold_out">(售完)0</option>
                            <option value="sparse">(稀少)1~99</option>
                            <option value="sufficient">(充足)100+</option>
                    </select>
                 </div>
                 <div class="d-flex justify-content-between">
                    <div>
                        <span class="switch switch-xs">
                            <input type="checkbox" class="switch" id="idChk-xs" name = "show_working" value="show">
                            <label for="idChk-xs">Only show the shop I work at</label>
                        </span>
                    </div>
                    <div>
                        <input type="submit" value = "Search" class="btn btn-primary btn-xs pull-right"></input>
                    </div>
                </div>
            </form>
            </div>
            </div>
        </div>
       <!-- <table id="show">   
        </table> -->
        
        <div class="container" id = "show_result">
            <div class="card bg-light mb-6" style="max-width:55rem;">
            <div class="card-header"><h4>Result</h4></div>
                <table class="table table-hover custab" id = "show">

                </table>
                <div id="not_found" class="card-body">
                </div>
                <div class = "col align-self-center card-body" style="padding:0px">
                    <nav aria-label="Page navigation" id = "page_number" >
                        <ul class="pagination justify-content-center" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
                
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">System notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </div>
            </div>
        </div>
    </body>
</html>
