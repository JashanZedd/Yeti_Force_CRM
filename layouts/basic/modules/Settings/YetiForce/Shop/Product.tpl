{*<!-- {[The file is published on the basis of YetiForce Public License 3.0 that can be found in the following directory: licenses/LicenseEN.txt or yetiforce.com]} -->*}
{strip}
	<!-- tpl-Settings-YetiForce-Shop-Product  -->
	{assign var=PRODUCT_ALERT value=$PRODUCT->showAlert()}
	<div class="dashboardWidget row no-gutters mb-3 pl-2 {if empty($PRODUCT->expirationDate)}bg-light u-bg-light-darken{elseif $PRODUCT_ALERT}bg-danger{else}bg-yellow{/if} js-product" data-js="showProductModal | click" data-product="{$PRODUCT->getName()}">
		<div class="col-sm-18 col-md-12 {if !empty($PRODUCT->expirationDate)} bg-white u-bg-white-darken{/if}">
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
					<div class="card-body h-100 d-flex flex-column">
						<h5 class="card-title u-cursor-pointer text-primary">{$PRODUCT->getLabel()}</h5>
						<p class="card-text truncate">{$PRODUCT->getIntroduction()}</p>
						{if empty($PRODUCT->expirationDate)}
							<button class="btn btn-dark btn-lg btn-block p-3 mt-auto js-buy-modal" data-js="showBuyModal | click" data-product="{$PRODUCT->getName()}">
								{if 'manual'===$PRODUCT->getPriceType()}
									{\App\Language::translate("LBL_SUPPORT_US", $QUALIFIED_MODULE)}
								{else}
									{$PRODUCT->getPrice()} {$PRODUCT->currencyCode} / {\App\Language::translate($PRODUCT->getPeriodLabel(), $QUALIFIED_MODULE)}
								{/if}
							</button>
						{else}
							{if $PRODUCT_ALERT}
								<div class="alert alert-danger">
									<span class="fas fa-exclamation-triangle"></span>
									{\App\Language::translate('LBL_SIZE_OF_YOUR_COMPANY_HAS_CHANGED', $QUALIFIED_MODULE)}
								</div>
								<button class="btn btn-danger btn-block mt-auto js-buy-modal"
								data-js="showBuyModal | click" data-product="{$PRODUCT->getName()}">
									{\App\Language::translate('LBL_SHOP_RENEW', $QUALIFIED_MODULE)}
								</button>
							{else}
								<button class="btn btn-block bg-yellow mt-auto js-buy-modal"
								data-js="showBuyModal | click" data-product="{$PRODUCT->getName()}" disabled>
									{\App\Fields\Date::formatToDisplay($PRODUCT->expirationDate)}
								</button>
							{/if}
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- /tpl-Settings-YetiForce-Shop-Product  -->
{/strip}
