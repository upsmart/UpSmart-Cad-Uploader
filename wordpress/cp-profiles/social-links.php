				<div id="socialLinks" class="left">
					<div class="fb-like slink" data-href= <?php echo '"' . "http://www.go-upsmart.com/company-profiles/?name=" .$company. '"' ?>  data-send="false" data-layout="button_count" data-width="450" data-show-faces="true" data-action="recommend" data-font="arial"></div>
					<div class="slink"> <a href= <?php echo '"' . "http://www.go-upsmart.com/company-profiles/?name=" .$company. '"' ?> class="twitter-share-button" data-show-count="false">Follow @company</a></div>
					<!-- Place this tag where you want the +1 button to render -->
					<div class="slink"> <g:plusone annotation="none" expandTo="right"></g:plusone> </div>
					<div class="slink rss">
                                        <?php $companyId = get_category_id($compName);
                                        echo "<a href= \"http://www.go-upsmart.com/?cat=".$companyId."&feed=rss2\" title=\"Subscribe to my feed\"><img src=\"/cp-profiles/images/icon-rss.png\"/></a>";
                                        ?>
                                        </div>
				</div>	