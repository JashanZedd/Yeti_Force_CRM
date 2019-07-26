{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<div class="modal-body js-data" data-product="{$PRODUCT->getName()}" data-js="data">
		<div class="row no-gutters" >
			<div class="col-sm-18 col-md-12">
				<div class="row">
					<div class="col-sm-4 col-md-3">
						{if $PRODUCT->getImage()}
							<img src="{$PRODUCT->getImage()}" class="grow thumbnail-image card-img-top intrinsic-item p-3"
								alt="{\App\Purifier::encodeHtml($PRODUCT->getLabel())}" title="{\App\Purifier::encodeHtml($PRODUCT->getLabel())}" />
						{else}
							<div class="product-no-image m-auto">
									<span class="fa-stack fa-2x product-no-image">
											<i class="fas fa-camera fa-stack-1x"></i>
											<i class="fas fa-ban fa-stack-2x"></i>
									</span>
							</div>
						{/if}
					</div>
					<div class="col-sm-11 col-md-7">
						<div class="card-body d-flex flex-column h-100">
							<h5 class="card-title text-primary">{$PRODUCT->getLabel()}</h5>
							<p class="card-text truncate">{$PRODUCT->getDescription()}</p>
							<div class="bg-dark text-white rounded-0 d-flex flex-nowrap text-nowrap align-items-center justify-content-center p-3 mt-auto" title="{\App\Language::translate('LBL_BUY', $QUALIFIED_MODULE)}">
								{if 'manual'===$PRODUCT->getPriceType()}
									{\App\Language::translate("LBL_SUPPORT_US", $QUALIFIED_MODULE)}
								{else}
									{$PRODUCT->getPrice()} {$PRODUCT->currencyCode} / {\App\Language::translate($PRODUCT->getPeriodLabel(), $QUALIFIED_MODULE)}
								{/if}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
{/strip}
