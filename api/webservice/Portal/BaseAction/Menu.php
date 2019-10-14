<?php
/**
 * Get elements of menu.
 *
 * @copyright YetiForce Sp. z o.o
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Tomasz Kur <t.kur@yetiforce.com>
 */

namespace Api\Portal\BaseAction;

/**
 * Action to get menu.
 */
class Menu extends \Api\Core\BaseAction
{
	/** @var string[] Allowed request methods */
	public $allowedMethod = ['GET'];

	/**
	 * Get method.
	 *
	 * @return array
	 *
	 * @OA\Get(
	 *		path="/webservice/BaseAction/Menu",
	 *		summary="Logs user into the system",
	 *		tags={"BaseAction"},
	 *		security={
	 *			{"basicAuth" : "", "ApiKeyAuth" : ""}
	 *    },
	 *		@OA\RequestBody(
	 *  			required=false,
	 *  			description="Base action menu request body",
	 *	  ),
	 *    @OA\Parameter(
	 *        name="X-ENCRYPTED",
	 *        in="header",
	 *        required=true,
	 * 				@OA\Schema(ref="#/components/schemas/X-ENCRYPTED")
	 *    ),
	 *		@OA\Response(
	 *				response=200,
	 *				description="Base action menu details",
	 *				@OA\JsonContent(ref="#/components/schemas/BaseActionMenuResponseBody"),
	 *				@OA\XmlContent(ref="#/components/schemas/BaseActionMenuResponseBody"),
	 *     		@OA\MediaType(
	 *         		mediaType="text/html",
	 *         		@OA\Schema(ref="#/components/schemas/BaseActionMenuResponseBody")
	 *     		),
	 *		),
	 * ),
	 * @OA\Schema(
	 * 		schema="BaseActionMenuResponseBody",
	 * 		title="Base action menu",
	 * 		description="Base action menu response body",
	 *		type="object",
	 *  	@OA\Property(
	 *       	property="status",
	 *        description="A numeric value of 0 or 1 that indicates whether the communication is valid. 1 - sukcess , 0 - error",
	 * 				enum={"0", "1"},
	 *     	  type="integer",
	 * 		),
	 *    @OA\Property(
	 *     	  property="result",
	 *     	 	description="Gets menu items",
	 *    	 	type="object",
	 * 				),
	 *    ),
	 * ),
	 */
	public function get()
	{
		return ['items' => \Settings_Menu_Record_Model::getCleanInstance()->getChildMenu($this->controller->app['id'], 0, \Settings_Menu_Record_Model::SRC_API)];
	}
}
