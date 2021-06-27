# Term Project Report

### 系統設定

- 在資料夾內，有Mask_system(3).sql 檔案要麻煩老師先匯入phpmyadmin中(在下圖上面區域)

    ![Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_6.07.37.png](Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_6.07.37.png)

### 系統功能摘要與測試範例教學

- 用途：輕量的購物網站
- 功能：提供使用者開設商店，及購買商品之相關功能，包含訂單及員工管理
- 系統架構與測試 ：
    1. 註冊頁面

        ![Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.18.27.png](Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.18.27.png)

        可以請老師建立一個自己的帳號，也可以故意將帳密或者電話輸入錯誤，會有錯誤提示

        - 有特別檢查，帳號密碼只能由大小寫英文數字所組成(case-sensitive)
        - 電話號碼為台灣電話號碼格式

    2. 登入頁面

        ![Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.16.54.png](Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.16.54.png)

        建立帳號以後，若密碼輸入錯誤，會跳出錯誤訊息

    3. 店家查詢,個人主頁

        ![Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.19.19.png](Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.19.19.png)

            Name的部分是採用部分匹配，舉例來說當我有店家AABB
        
            當我search AB , AABB因為有部分匹配所以他會被選出來
        
            建議可以先不輸入條件直接搜搜尋

    4. 訂單購買(要先search 才會顯示店家)

        ![Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_6.21.33.png](Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_6.21.33.png)

        在方框欄位內輸入欲購買的數量，按下 shop即可完成下訂

    5. 個人訂單管理

        ![Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.20.11.png](Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.20.11.png)

    6. 店家管理-價格/數量/員工

        ![Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.21.05.png](Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.21.05.png)

        ![Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.21.18.png](Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.21.18.png)

    7. 店家訂單管理

        ![Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.20.48.png](Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.20.48.png)

### 系統開發平台

- 開發工具：PHP,JavaScript , CSS , HTML
- 框架/程式庫：JQuery , bootstrap5
- 開發環境：MacOs
- 伺服器：Xampp , PHPMyAdmin ,MySQL
- 執行環境：MacOs

### 重點程式說明

- 整體概念及架構
    - 後端
        - ER-model

            ![Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-04-12_8.03.13.png](Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-04-12_8.03.13.png)

        - 資料庫資料處理：

            抓取資料的部分主要是採用ajax,進行非同步的抓取，提高使用體驗

            在網頁上，我以一個jsArray的global variable來存取資料庫抓回來的所有資料,減少查詢資料庫之次數

    - 前端
        - 主要採用bootstrap

- 函數說明
    - pagination

        ```jsx
        function generate_pagination(){
                        let data_length = jsArray.length; 
                        let per_page = 5;單頁顯示資料數
                        let total = Math.ceil(data_length/5);
                        
                        $('#show tbody').empty(); //先淨空寫入的目的地
                        if(total===0){//如果資料數為0,則顯示無搜尋結果
                            $('#not_found').html("<span id = \"span_error_msg\ class=\"text-danger\">cannot found the matching result</span>");
                            $('#not_found').show();
                        }
                        else{//若有資料則開始畫table
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
                        if(total==1){//若只有單頁，則不需執行分頁
                            $('#show').append("<tbody>");
                            for(let cnt = 0;cnt < data_length ; cnt++ ){
                                console.log('cnt',cnt);
                                draw(cnt);                
                            }
                            
                            $('#show').append("</tbody>");
                        }
                        else if(total!=0){//若有超過兩頁則執行分頁
                            window.pagObj = $('#pagination').twbsPagination({
                            totalPages: total,
                            visiblePages: 5,//
                            onPageClick: function (event, page) {
                                var tableHeaderRowCount = 1;
                                var table = document.getElementById('show');
                                var rowCount = table.rows.length;
                                for (var i = tableHeaderRowCount; i < rowCount; i++) {
                                    table.deleteRow(tableHeaderRowCount);
                                }
                                $('#show').append("<tbody>"); //寫入資料
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
        ```

    - Sorting

        ```jsx
        function sortjsArray(idx,mode){
              jsArray.sort(
                  function(a,b){
                      return ((mode)?(parseInt(a[idx])-parseInt(b[idx])):(parseInt(b[idx])-parseInt(a[idx])));;
                  }
              );
          }
        ```

        - 此部分主要是實作object的compare function,來提供價格或數量的排序

    - Serialize Object

        ```jsx
        $.fn.serializeObject = function(){
                        var obj = {};
                        var delete_idx = [];
                        $.each( this.serializeArray(), function(i,o){
                        var n = o.name,
                            v = o.value,
                            tmp_v = o.value;
                            console.log(n,v);
                            if(n==="multiselect[]"){
                                jsArray[v]['status'] = update_mode;
                                jsArray[v]['EndTime'] = "<?php
                                    $dateTime = new DateTime;
                                    echo($dateTime->format('Y-m-d H:i:s'));
                                ?>";
                                jsArray[v]['Finish_user'] = "<?php
                                        echo($_SESSION['Username']);
                                    ?>";
                                if(search_condition!="all" &&update_mode!=search_condition &&  jsArray != undefined ){
                                    //console.log(jsArray);
                                    //jsArray.splice(v,1);
                                    delete_idx.push(v);
                                }
                                v = jsArray[v]['OID'];
                                
                            } 
                            obj[n] = obj[n] === undefined ? v
                            : $.isArray( obj[n] ) ? obj[n].concat( v )
                            : [ obj[n], v ];
                        });
        ```

        - 此函數，主要是將表單內容轉為，Object 方便以ajax 送出至伺服器處理
        - 在此運用jquery之fn ,將這個funcion掛載在所有的網頁元素上。
        - 在程式的尾段，有多一個特判是為了要處理multi-select check box,當遇到multi-value時便會擴展成array
            - 範例執行結果

                ![Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.48.12.png](Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.48.12.png)

    - 彈出視窗的製作

        ![Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.50.24.png](Term%20Project%20Report%207a7f9cb513174f77a052fc790ef0a84c/_2021-06-12_5.50.24.png)

        ```jsx
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
        ```

        此部分是採用bootstarp 之modal來製作，主要是因為alert很醜>_<

        可以概略分為3部分:

        1. header : 標題
        2. body ：內容
        3. footer ：下方按鈕

    - 輸入長度檢查函數

        ```jsx
        function check_length(obj,len,error_loc,name){
              if(obj.length>len){
                  document.getElementsByName(name)[0].value = obj.slice(0,len);
                  document.getElementById(error_loc).innerHTML = "The maximum lenght should less than " + len;
              }
          }
        ```

        搭配oninput event listener 動態抓取當前輸入字串的長度，並即時更新錯誤訊息

    - 資料抓取範例

        ```jsx
        $('form.login').on('submit', function(e) {
                e.preventDefault();
                formData_for_search = $(this).serializeObject();
                //console.log(formData);
                $.ajax({
                    type: "POST",
                    url: "search_order.php",
                    data:formData_for_search , // serializes the form's elements.
                    success: function(data)
                    {  
                        current_page = 1;
                        //console.log(data);
                        jsArray= JSON.parse(data);
                        //console.log(jsArray);
                        generate_pagination();
                        update_table();
                    }
                });
            });
        ```

        為了避免畫面閃爍，在本次作業中，抓取資料的部分均採用ajax執行。概念如下：

        1. 透過serializeObject() , 先將表單內容轉成Object
        2. 接著使用jQuery 運用ajax將資料送出
        3. 接著資料回傳進入success function後在更新頁面內容，並產生分頁

### 結論與心得

- 會有這次作業的契機主要是因為本人本身沒有什麼美感，很多遊戲也都被做過了，剛好正值疫情嚴峻，那乾脆就來寫一個口罩的訂購系統，並將老師在學期後半段所教的jQuery , Ajax , bootstrap 搭配自學php,將這次作業完成。
- 比較滿意的地方：
    1. 在讀取資料時，畫面不會跳轉 → 個人覺得看起來比較舒服
    2. 在多人使用下，搭配一些function的判定，可以偵測是否有資料過時的問題，並可以給出訊息
    3. 分頁功能
    4. 排序功能
    5. 建立帳號或輸入資料時，可以動態的顯示錯誤資訊
- 誌謝：最後還是要謝謝老師在課堂中教導這麼多內容，才能讓我完成這份作業，謝謝老師！