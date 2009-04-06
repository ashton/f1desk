<?php handleLanguage(__FILE__);?>
  <!--[ERROR/OK BOX]-->
    <?= ErrorHandler::getNotice(); ?>
  <!--[ERROR/OK BOX]-->
<? if ( getSessionProp('isSupporter')=="true" ):?>
		<span class="homeBoxTitle"><?=BOOK_MARK?></span>
		<span class="homeBoxTitle loadingRequest" id="bookmarkLoading"><img src="<?= TEMPLATEDIR ?>images/loading.gif"> Carregando...</span>
		<div id="bookmarkBoxContent" class="homeBoxContent">
			<table class="tableTickets" id="bookmarkTable">
				<thead>
					<th><?=ID_TICKET?></th>
					<th><?=BOOKMARK_TITLE?></th>
					<th width="20%"><?=BOOKMARK_ACTIONS?></th>
				</thead>
				<tbody>
          <?=TemplateHandler::showBookmarkedTickets($ArBookmark);?>
				</tbody>
			</table>
		</div>
<?endif;?>