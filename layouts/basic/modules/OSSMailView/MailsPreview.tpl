{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	{assign var=COUNT value=count($RECOLDLIST)}
	<div class="modelContainer modal fade" tabindex="-1">
		<div class="modal-dialog modal-full">
			<div class="modal-content">
				<div class="modal-header">
					<div class="d-flex justify-content-between w-100">
						<div>
							<h5 class="modal-title">{\App\Language::translate('LBL_RECORDS_LIST','OSSMailView')}</h5>
						</div>
						<div>
							<button type="button" class="btn btn-outline-secondary expandAllMails mr-2">
								{\App\Language::translate('LBL_EXPAND_ALL','OSSMailView')}
							</button>
							<button type="button" class="btn btn-outline-secondary collapseAllMails">
								{\App\Language::translate('LBL_COLLAPSE_ALL','OSSMailView')}
							</button>
						</div>
						<div>
							<h5 class="modal-title">{\App\Language::translate('LBL_COUNT_ALL_MAILS','OSSMailView')}: {$COUNT}</h5>
						</div>
						<div>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
					</div>
				</div>
				<div class="modal-body modalViewBody">
					<div class="mailsList">
						<div class="container-fluid">
							{foreach from=$RECOLDLIST item=ROW key=KEY}
								<div class="row{if $KEY%2 != 0} even{/if}">
									{if \App\Privilege::isPermitted('OSSMailView', 'DetailView', $ROW['id'])}
										<div class="col-md-12 mailActions">
											<div class="float-left">
												<a title="{\App\Language::translate('LBL_SHOW_PREVIEW_EMAIL','OSSMailView')}" class="showMailBody btn btn-sm btn-light" >
													<span class="body-icon fas fa-caret-down"></span>
												</a>&nbsp;
												<button type="button" class="btn btn-sm btn-light showMailModal" data-url="{$ROW['url']}" title="{\App\Language::translate('LBL_SHOW_PREVIEW_EMAIL','OSSMailView')}">
													<span class="body-icon fas fa-search"></span>
												</button>
											</div>
											<div class="float-right">
												{if AppConfig::main('isActiveSendingMails') && \App\Privilege::isPermitted('OSSMail')}
													{if $USER_MODEL->get('internal_mailer') == 1}
														{assign var=COMPOSE_URL value=OSSMail_Module_Model::getComposeUrl($SMODULENAME, $SRECORD, 'Detail')}
														<button type="button" class="btn btn-sm btn-light sendMailBtn" data-url="{$COMPOSE_URL}&mid={$ROW['id']}&type=reply" data-popup="{$POPUP}" title="{\App\Language::translate('LBL_REPLY','OSSMailView')}">
															<img width="14px" src="{\App\Layout::getLayoutFile('modules/OSSMailView/previewReply.png')}" alt="{\App\Language::translate('LBL_REPLY','OSSMailView')}">
														</button>
														<button type="button" class="btn btn-sm btn-light sendMailBtn" data-url="{$COMPOSE_URL}&mid={$ROW['id']}&type=replyAll" data-popup="{$POPUP}" title="{\App\Language::translate('LBL_REPLYALLL','OSSMailView')}">
															<img width="14px" src="{\App\Layout::getLayoutFile('modules/OSSMailView/previewReplyAll.png')}" alt="{\App\Language::translate('LBL_REPLYALLL','OSSMailView')}">
														</button>
														<button type="button" class="btn btn-sm btn-light sendMailBtn" data-url="{$COMPOSE_URL}&mid={$ROW['id']}&type=forward" data-popup="{$POPUP}" title="{\App\Language::translate('LBL_FORWARD','OSSMailView')}">
															<span class="fas fa-share"></span>
														</button>
													{else}
														<a class="btn btn-sm btn-light" href="{OSSMail_Module_Model::getExternalUrlForWidget($ROW, 'reply')}" title="{\App\Language::translate('LBL_CREATEMAIL', 'OSSMailView')}">
															<img width="14px" src="{\App\Layout::getLayoutFile('modules/OSSMailView/previewReply.png')}" alt="{\App\Language::translate('LBL_REPLY','OSSMailView')}">
														</a>
														<a class="btn btn-sm btn-light" href="{OSSMail_Module_Model::getExternalUrlForWidget($ROW, 'replyAll')}" title="{\App\Language::translate('LBL_REPLYALLL', 'OSSMailView')}">
															<img width="14px" src="{\App\Layout::getLayoutFile('modules/OSSMailView/previewReplyAll.png')}" alt="{\App\Language::translate('LBL_REPLYALLL','OSSMailView')}">
														</a>
														<a class="btn btn-sm btn-light" href="{OSSMail_Module_Model::getExternalUrlForWidget($ROW, 'forward')}" title="{\App\Language::translate('LBL_FORWARD', 'OSSMailView')}">
															<span class="fas fa-share"></span>
														</a>
													{/if}

												{/if}
											</div>
											<div class="clearfix"></div>
											<hr/>
										</div>
									{/if}
									<div class="col-md-12">
										<div class="float-left">
											<span class="firstLetter">
												{$ROW['firstLetter']}
											</span>
										</div>
										<div class="float-right muted">
											<small>
												{\App\Fields\DateTime::formatToViewDate($ROW['date'])}
											</small>   
										</div>
										<h5 class="u-text-ellipsis mailTitle mainFrom">
											{$ROW['from']}
										</h5>
										<div class="float-right">
											{if $ROW['attachments'] eq 1}
												<img class="float-right" src="{\App\Layout::getLayoutFile('modules/OSSMailView/attachment.png')}" />
											{/if}
											<span class="float-right">
												{if $ROW['type'] eq 0}
													<span class="fas fa-arrow-up text-success"></span>
												{elseif $ROW['type'] eq 1}
													<span class="fas fa-arrow-down text-danger"></span>
												{elseif $ROW['type'] eq 2}
													<span class="fas fa-retweet text-primary"></span>
												{/if}
											</span>
											<span class="float-right smalSeparator"></span>
										</div>
										<h5 class="u-text-ellipsis mailTitle mainSubject">
											{$ROW['subject']}
										</h5>
									</div>
									<div class="col-md-12">
										<hr/>
									</div>
									<div class="col-md-12">
										<div class="mailTeaser">
											{$ROW['teaser']}
										</div>	
									</div>
									<div class="col-md-12 mailBody d-none">
										<div class="mailBodyContent">{$ROW['body']}</div>
									</div>
									<div class="clearfix"></div>
								</div>
							{/foreach}
							{if $COUNT == 0}
								<p class="textAlignCenter">{\App\Language::translate('LBL_NO_MAILS','OSSMailView')}</p>
							{/if}
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{/strip}
