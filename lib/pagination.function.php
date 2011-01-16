<? 
	function genPagination($current,$total,$url){ 
	?>
    <div class="pagination">
        <div>
            <ol>
                <li class="info">
                    <span>
                        Page
                        <?=$current?>
                            of
                            <?=$total?>
                    </span>
                </li>
				<?if($current!=1){?>
                <li class="prev">
                    <a href="<?=str_replace("{pageid}",$current-1,$url)?>">
                        <span>
                            Previous Page
                        </span>
                    </a>
                </li>
				<?}?>
				<li <?=$current==1?"class='current'":""?>>
                    <a title="Page 1" href="<?=str_replace("{pageid}",1,$url)?>">
                        <span>
                            1
                        </span>
                    </a>
                </li>
				<?
					if($current>=5){
						?>
				<li class="gap">
                    <span>
                        ...
                    </span>
                </li>
						<?}?>
				<?
				$i=$current>=5?$current-2:2;
				$e = $i+4;
				while($i<=$total&&$i<=$e) {
				?>
                <li <?=$current==$i?"class='current'":""?>>
                    <a title="Page <?=$i?>" href="<?=str_replace("{pageid}",$i,$url)?>">
                        <span>
                            <?=$i?>
                        </span>
                    </a>
                </li>
				<?$i++;
					}?>
				<?if($total-$current>3) {?>
                <li class="gap">
                    <span>
                        ...
                    </span>
                </li>
				<?}?>
				<?if($total-$current>=3) {?>
                <li <?=$current==$total?"class='current'":""?>>
                    <a title="Page <?=$total?>" href="<?=str_replace("{pageid}",$total,$url)?>">
                        <span>
                            <?=$total?>
                        </span>
                    </a>
                </li>
				<?}?>
				<?if($current!=$total) {?>
                <li class="next">
                    <a href="<?=str_replace("{pageid}",$current+1,$url)?>">
                        <span>
                            Next Page
                        </span>
                    </a>
                </li>
				<?}?>
            </ol>
        </div>
    </div>
    <? }
