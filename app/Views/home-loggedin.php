<div class="col-md-12">
    <div class="card">
		Herzlich willkommen mit Mitgliederbereich der TGÖ Services App.
    </div>
</div>

<div class="col-md-6">
	<div class="card card-primary">
        <?php 
        $isFirstHeadline = true;
        foreach( $ci->getMenuitems() as $key => $menuItemInfo) {
            if( $menuItemInfo[0] === null ) {
                if( !$isFirstHeadline ) {
                    //close old panel and open new one
                    ?>
                    	</div>
                    </div>   
                    <div class="col-md-6">
                    	<div class="card card-primary">                    
                    <?php 
                }
                
                $isFirstHeadline = false;
                ?>
                  <div class="card-header">
                    <h3 class="card-title"><?= esc($menuItemInfo[1]) ?></h3>
                  </div>
                <?php 
            }
            else
            {
                $isActive = $key == $ci->getActiveMenuItem();
                ?>
                	<button type="submit" onclick="window.location.href='<?= esc($menuItemInfo[2]) ?>';"
                		class="btn btn-outline-secondary btn-block">
                		<i class="<?= esc($menuItemInfo[0]) ?>"></i> <?= esc($menuItemInfo[1]) ?>
            		</button>
        		<?php 
            }
        }
        ?>
	</div>
</div>        