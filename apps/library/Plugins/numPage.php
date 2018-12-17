<?php
namespace Bpai\Plugins;

/*  
 * PHP分页类  
 * @package Page  
 * @Created 2015-07-20 15:00
 * Example:  
       $myPage=new Pager(1300,intval($CurrentPage));  
       $pageStr= $myPage->GetPagerContent();  
       echo $pageStr;  
 */
class numPage {
    private $pageSize = 10;
    private $pageIndex;
    private $totalNum;

    private $totalPagesCount;

    private $pageUrl;
    private static $_instance;

    public function __construct($p_totalNum, $p_pageIndex=1, $p_pageSize = 10,$p_initNum=3,$p_initMaxNum=5) {
        if (! isset ( $p_totalNum ) || !isset($p_pageIndex)) {
            die ( "pager initial error" );
        }

        $this->totalNum = $p_totalNum;
        $this->pageIndex = $p_pageIndex;
        $this->pageSize = $p_pageSize;
        $this->initNum=$p_initNum;
        $this->initMaxNum=$p_initMaxNum;
        $this->totalPagesCount= ceil($p_totalNum / $p_pageSize);
        $this->pageUrl=$this->_getPageUrl();

        $this->_initPagerLegal();
    }


    /**
     * 获取去除page部分的当前URL字符串
     *
     * @return String URL字符串
     */
    private function _getPageUrl() {
        $CurrentUrl = $_SERVER["REQUEST_URI"];
        $arrUrl     = parse_url($CurrentUrl);
        $urlQuery   = $arrUrl["query"];
        if($urlQuery){
            /* echo "<pre>";
             print_r($CurrentUrl);///seller/batchcheck/index?page=2
             print_r($arrUrl/*Array
                                     (
                                         [path] => /seller/batchcheck/index
                                         [query] => page=2
                                     )
            print_r($urlQuery);//page=2*/

//var_dump($urlQuery);
//            var_dump($this->pageIndex);
            $preg = '/(^|&)page='.$this->pageIndex.'/' ;
//            var_dump($preg);
            $urlQuery  = preg_replace($preg, '', $urlQuery);
//var_dump($urlQuery);
            $CurrentUrl = str_replace($arrUrl["query"], $urlQuery, $CurrentUrl);
//            var_dump($CurrentUrl);exit;
//exit;
            if($urlQuery){
                $CurrentUrl.="&page";
            }
            else
            {
                $CurrentUrl.="page";
            }
        } else {
            $CurrentUrl.="?page";
        }

        return $CurrentUrl;

    }
    /*
     *设置页面参数合法性
     *@return void
    */
    private function _initPagerLegal()
    {
        if((!is_numeric($this->pageIndex)) ||  $this->pageIndex<1)
        {
            $this->pageIndex=1;
        }elseif($this->pageIndex > $this->totalPagesCount)
        {
            $this->pageIndex=$this->totalPagesCount;
        }



    }
    
    public function GetPager(){
        $str = "<ul>";
        
                //首页 上一页
        if($this->pageIndex==1){
            $str .="<li class='active'><a href='#'>上一页</a></li>";
        }else{
            $str .="<li><a href='{$this->pageUrl}=".($this->pageIndex-1)."'>上一页</a></li>";
        }        
                
        //10页（含）以下
        $currnt="";
        if($this->totalPagesCount<=10)
        {
            for($i=1;$i<=$this->totalPagesCount;$i++)

            {
                if($i==$this->pageIndex)
                {
                    $currnt=" class='active'";
                }
                else
                {
                    $currnt="";
                }
                $str .="<li {$currnt}><a href='{$this->pageUrl}={$i} ' >$i</a></li>";
            }
        }
        else                                //10页以上
        {
            if($this->pageIndex<3)  //当前页小于3
            {
                for($i=1;$i<=3;$i++)
                {
                    if($i==$this->pageIndex)
                    {
                        $currnt=" class='active'";
                    }
                    else
                    {
                        $currnt="";
                    }
                     $str .="<li {$currnt}><a href='{$this->pageUrl}={$i} ' >$i</a></li>";
                }

                //$str.="<span class=\"dot\">……</span>"."\n";

                for($i=$this->totalPagesCount-3+1;$i<=$this->totalPagesCount;$i++)//功能1
                {
                    $str .="<li><a href='{$this->pageUrl}={$i} ' >$i</a></li>";
                }
            }
            elseif($this->pageIndex<=5)   //   5 >= 当前页 >= 3
            {
                for($i=1;$i<=($this->pageIndex+1);$i++)
                {
                    if($i==$this->pageIndex)
                    {
                        $currnt=" class='active'";
                    }
                    else
                    {
                        $currnt="";
                    }
                    $str .="<li {$currnt}><a href='{$this->pageUrl}={$i} ' >$i</a></li>";
                }
                //$str.="<span class=\"dot\">……</span>"."\n";

                for($i=$this->totalPagesCount-3+1;$i<=$this->totalPagesCount;$i++)//功能1
                {
                   $str .="<li><a href='{$this->pageUrl}={$i} ' >$i</a></li>";
                }

            }
            elseif(5<$this->pageIndex  &&  $this->pageIndex<=$this->totalPagesCount-5 )             //当前页大于5，同时小于总页数-5
            {

                for($i=1;$i<=3;$i++)
                {
                    $str .="<li><a href='{$this->pageUrl}={$i} ' >$i</a></li>";
                }
                //$str.="<span class=\"dot\">……</span>";
                for($i=$this->pageIndex-1 ;$i<=$this->pageIndex+1 && $i<=$this->totalPagesCount-5+1;$i++)
                {
                    if($i==$this->pageIndex)
                    {
                        $currnt=" class='active'";
                    }
                    else
                    {
                        $currnt="";
                    }
                     $str .="<li {$currnt}><a href='{$this->pageUrl}={$i} ' >$i</a></li>";
                }
                //$str.="<span class=\"dot\">……</span>";

                for($i=$this->totalPagesCount-3+1;$i<=$this->totalPagesCount;$i++)
                {
                    $str .="<li><a href='{$this->pageUrl}={$i} ' >$i</a></li>";

                }
            }
            else
            {

                for($i=1;$i<=3;$i++)
                {
                    $str .="<li><a href='{$this->pageUrl}={$i} ' >$i</a></li>";
                }
                //$str.="<span class=\"dot\">……</span>"."\n";

                for($i=$this->totalPagesCount-5;$i<=$this->totalPagesCount;$i++)//功能1
                {
                    if($i==$this->pageIndex)
                    {
                        $currnt=" class='active' ";
                    }
                    else
                    {
                        $currnt="";
                    }
                    $str .="<li {$currnt}><a href='{$this->pageUrl}={$i} ' >$i</a></li>";

                }
            }

        }                
        /*
        除首末后 页面分页逻辑结束
        */
        //下一页 末页
        if($this->pageIndex==$this->totalPagesCount)
        {
            $str .="<li class='active'><a href='#'>下一页</a></li>";
        } 
        else
        {
            $str .="<li><a href='{$this->pageUrl}=".($this->pageIndex+1)."'>下一页</a></li>";
        }
        //$str .= '<a class="num">共'.$this->totalPagesCount.'页,</a></div>';
       $str .="</ul>";
       return $str;         
        
    }
    
    
//$this->pageUrl}={$i}   
//{$this->CurrentUrl}={$this->TotalPages}   
    public function GetPagerContent($models='') {
        $str = "<div id='pagenav' class=\"table_page \">";
        if($models)
        {
            $str .= '<div class="page_right"><p>到第&nbsp;<input type="text" class="current_page">&nbsp;页</p><a class="page_sure" href="">确定</a></div>';
            $str .= "<div class=\"all_page page\"><span class='num'>共".$this->totalNum."条</span>";
            //首页 上一页
            if($this->pageIndex==1)
            {
    //            $str .="<a href='javascript:void(0)' class='tips' title='首页'>首页</a> "."\n";
                $str .="<a href='javascript:void(0)' class='prev' title='上一页'>上一页</a> "."\n"."\n";
            }else
            {
    //            $str .="<a href='{$this->pageUrl}=1' class='tips' title='首页'>首页</a> "."\n";
                $str .="<a href='{$this->pageUrl}=".($this->pageIndex-1)."' class='prev' title='上一页'>上一页</a> "."\n"."\n";
            }
        }
        //echo $str;exit();
        /*除首末后 页面分页逻辑*/
        //10页（含）以下
        $currnt="";
        if($this->totalPagesCount<=10)
        {
            for($i=1;$i<=$this->totalPagesCount;$i++)
            {
                if($i==$this->pageIndex)
                {
                    $currnt=" class='current_num active'";
                }
                else
                {
                    $currnt="class='current_num' ";
                }
                $str .="<a href='{$this->pageUrl}={$i} ' {$currnt} >$i</a>"."\n" ;
            }
        }else                                //10页以上
        {
            if($this->pageIndex<3)  //当前页小于3
            {
                for($i=1;$i<=3;$i++)
                {
                    if($i==$this->pageIndex)
                    {
                        $currnt=" class='current_num active'";
                    }
                    else
                    {
                        $currnt="class='current_num' ";
                    }
                    $str .="<a href='{$this->pageUrl}={$i} ' {$currnt} >$i</a>"."\n" ;
                }
                $str.="<span class=\"dot\">……</span>"."\n";

                for($i=$this->totalPagesCount-3+1;$i<=$this->totalPagesCount;$i++)//功能1
                {
                    $str .="<a href='{$this->pageUrl}={$i}' >$i</a>"."\n" ;

                }
            }
            elseif($this->pageIndex<=5)   //   5 >= 当前页 >= 3
            {
                for($i=1;$i<=($this->pageIndex+1);$i++)
                {
                    if($i==$this->pageIndex)
                    {
                        $currnt=" class='current_num active'";
                    }
                    else
                    {
                        $currnt="class='current_num' ";
                    }
                    $str .="<a href='{$this->pageUrl}={$i} ' {$currnt} >$i</a>"."\n" ;
                }
                $str.="<span class=\"dot\">……</span>"."\n";

                for($i=$this->totalPagesCount-3+1;$i<=$this->totalPagesCount;$i++)//功能1
                {
                    $str .="<a href='{$this->pageUrl}={$i}'  >$i</a>"."\n" ;

                }

            }
            elseif(5<$this->pageIndex  &&  $this->pageIndex<=$this->totalPagesCount-5 )             //当前页大于5，同时小于总页数-5
            {

                for($i=1;$i<=3;$i++)
                {
                    $str .="<a href='{$this->pageUrl}={$i}' >$i</a>"."\n" ;
                }
                $str.="<span class=\"dot\">……</span>";
                for($i=$this->pageIndex-1 ;$i<=$this->pageIndex+1 && $i<=$this->totalPagesCount-5+1;$i++)
                {
                    if($i==$this->pageIndex)
                    {
                        $currnt=" class='current_num active'";
                    }
                    else
                    {
                        $currnt="class='current_num' ";
                    }
                    $str .="<a href='{$this->pageUrl}={$i} ' {$currnt} >$i</a>"."\n" ;
                }
                $str.="<span class=\"dot\">……</span>";

                for($i=$this->totalPagesCount-3+1;$i<=$this->totalPagesCount;$i++)
                {
                    $str .="<a href='{$this->pageUrl}={$i}' >$i</a>"."\n" ;

                }
            }
            else
            {

                for($i=1;$i<=3;$i++)
                {
                    $str .="<a href='{$this->pageUrl}={$i}' >$i</a>"."\n" ;
                }
                $str.="<span class=\"dot\">……</span>"."\n";

                for($i=$this->totalPagesCount-5;$i<=$this->totalPagesCount;$i++)//功能1
                {
                    if($i==$this->pageIndex)
                    {
                        $currnt=" class='current_num active'";
                    }
                    else
                    {
                        $currnt="class='current_num' ";
                    }
                    $str .="<a href='{$this->pageUrl}={$i} ' {$currnt} >$i</a>"."\n" ;

                }
            }
        }

        /*
        除首末后 页面分页逻辑结束
        */
        if($models)
        {
            //下一页 末页
            if($this->pageIndex==$this->totalPagesCount)
            {
                $str .="\n"."<a href='javascript:void(0)' class='next' title='下一页'>下一页</a>"."\n" ;
            } 
            else
            {
                $str .="\n"."<a href='{$this->pageUrl}=".($this->pageIndex+1)."' class='next' title='下一页'>下一页</a> "."\n";
            }
            $str .= '<a class="num">共'.$this->totalPagesCount.'页,</a></div>';
        }
        else
        {
            $str .= "<p class='page_num'>$this->pageIndex/$this->totalPagesCount</p></div>";
        }

        return $str;
    }

    /**
     * 获得实例
     * @return
     */
  static public function getInstance() {
      if (is_null ( self::$_instance )) {
          self::$_instance = new pager ();
      }
      return self::$_instance;
  }
}