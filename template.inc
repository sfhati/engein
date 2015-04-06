
  use like [var:"variable"end var]
  [var:"variable"end var] //print echo $variable;
  [var:"variable-var"end var] //print $variable;
  [var:"variable-sess"end var] //print echo $_SESSION[variable];
  [var:"variable-sess-var"end var] //print $_SESSION[variable];
  [var:"variable[word]"end var] //print echo $variable[word];
  [var:"variable[word-var]"end var] //print  $variable[word];
  [var:"variable[word]-var"end var] //print  $variable[word];
  [var:"variable-cons"end var] //print echo variable;
 
kldsja flkdsjaflkdjsafds<br> fdsa fdsaf dsaf sdaf<br>fdsaf sdaf asdic
kldsja flkdsjaflkdjsafds<br> fdsa fdsaf dsaf sdaf<br>fdsaf sdaf asdic
kldsja flkdsjaflkdjsafds<br> fdsa fdsaf dsaf sdaf<br>fdsaf sdaf asdic
[var:"ERROR[PAGE_NOT_FOUND]-var"end var]
[var:"ERROR[PAGE_NOT_FOUND]"end var]
[if:"[var:"ERROR[PAGE_NOT_FOUND]-var"end var]","
404 page not found! <br>
[else]
 [if:"[var:"ccx-var"end var]==[var:"arr[bas]-var"end var]","
 
   
 inside
 
 "end if]
"end if]
[if:"[var:"ERROR[PAGE_NOT_ACTIVE]-var"end var]","[var:"bassam"end var]
403 page not active!<br>

fdsgtgfds dfgsgf
[else]
this is else code
"end if]
[if:"[var:"ERROR[PAGE_NOT_PERMISSION]-var"end var]","
500 you don't have permission!<br>
<br>


"end if]  
[include:"template_include"end include]
kldsja flkdsjaflkdjsafds<br> fdsa fdsaf dsaf sdaf<br>fdsaf sdaf asdic
kldsja flkdsjaflkdjsafds<br> fdsa fdsaf dsaf sdaf<br>fdsaf sdaf asdic

                    <div class="heap">
                        <ul class="heapOptions">
                            [each:"information_cpanel","   
                            <li class="heapOption"  title="%information_cpanel:val%">
                                <a href='javascript:'>
                                     [if:"%information_cpanel:key-var%==bas2","Yes its bas2!"end if]
                                    <p>[%information_cpanel:key%]</p>[var:"bassam","print "end var]
                                    <span>%information_cpanel:val%</span>
                                    <span>%information_cpanel:val[bassam1]%</span>
                                    <span>%information_cpanel:val['bassam essawi']%</span>
                                    <span>%information_cpanel:val[3443]%</span>
                                    <span>%information_cpanel:val[thisisvar-var]%</span>
                                    <span>%information_cpanel:%</span>
                                    <span>%information_cpanel:#%</span>
                                </a>
                            [each:"session_array-sess","   
                            <li class="heapOption"  title="%session_array:val%">
                            <li class="heapOption"  title="%session_array:key%">
                             "end each]   
                            </li>

                            "end each]
                        </ul>
                    </div>


<ul>
    [for:"uu","4","10","
    <li>%uu%</li>
    [if:"%uu-var%==6","Yes its six!"end if]
    "end for]    
</ul>
 [for:"lop","4","10","this is %lop% , so can use as a var like %lop-var% "end for] 

[element:"text","",""end element]
