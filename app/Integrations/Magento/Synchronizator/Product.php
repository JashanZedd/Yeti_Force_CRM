<?php

/**
 * Synchronize products.
 *
 * @package Integration
 *
 * @copyright YetiForce Sp. z o.o
 * @license   YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author    Arkadiusz Dudek <a.dudek@yetiforce.com>
 * @author    Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */

namespace App\Integrations\Magento\Synchronizator;

/**
 * Product class.
 */
class Product extends Record
{
	/**
	 * {@inheritdoc}
	 */
	public function process(): void
	{
		$this->lastScan = $this->config->getLastScan('product');
		if (!$this->lastScan['start_date'] || (0 === (int) $this->lastScan['id'] && 0 === (int) $this->lastScan['idcrm'] && $this->lastScan['start_date'] === $this->lastScan['end_date'])) {
			$this->config->setScan('product');
			$this->lastScan = $this->config->getLastScan('product');
		}
		$this->getProductSkuMap();
		$this->getMapping('product');
		if ($this->checkProductsCrm() && $this->checkProducts()) {
			$this->config->setEndScan('product', $this->lastScan['start_date']);
		}
	}

	/**
	 * Method to save, update or delete products from YetiForce.
	 */
	public function checkProductsCrm(): bool
	{
		$result = false;
		$productsCrm = $this->getProductsCrm();
		if (!empty($productsCrm)) {
			$products = $this->getProducts($this->getFormattedRecordsIds(array_keys($productsCrm), self::MAGENTO, 'product'));
			foreach ($productsCrm as $id => $productCrm) {
				if (isset($this->map['product'][$id], $products[$this->map['product'][$id]])) {
					$productData = $this->getProductFullData($this->mapIdToSku[$this->map['product'][$id]]);
					$checkImages = $this->checkImages($productData, $productCrm);
					$hasChanges = !empty($productData) && $this->hasChanges($productCrm, $productData);
					if (!empty($checkImages) || $hasChanges) {
						if (self::MAGENTO === $this->whichToUpdate($productCrm, $productData)) {
							if ($hasChanges) {
								$this->updateProduct($this->map['product'][$id], $productCrm);
							}
							if (!empty($checkImages['add']) || !empty($checkImages['remove'])) {
								$this->updateImages($this->mapIdToSku[$this->map['product'][$id]], $checkImages);
							}
						} else {
							if ($hasChanges) {
								$this->updateProductCrm($id, $productData);
							}
							$this->updateImagesCrm($id, $checkImages['addCrm'] ?? []);
						}
					}
				} elseif (isset($this->map['product'][$id]) && !isset($products[$this->map['product'][$id]])) {
					$this->deleteProductCrm($id);
				} else {
					$this->saveProduct($productCrm);
				}
				$this->config->setScan('product', 'idcrm', $id);
			}
		} else {
			$result = true;
		}
		return $result;
	}

	/**
	 * Method to save, update or delete products from Magento.
	 *
	 * @return bool
	 */
	public function checkProducts(): bool
	{
		$allChecked = false;
		try {
			$products = $this->getProducts();
			if (!empty($products)) {
				$productsCrm = $this->getProductsCrm($this->getFormattedRecordsIds(array_keys($products), self::YETIFORCE, 'product'));
				foreach ($products as $id => $product) {
					$productData = $this->getProductFullData($product['sku']);
					if (empty($productData)) {
						continue;
					}
					if (isset($this->mapCrm['product'][$id], $productsCrm[$this->mapCrm['product'][$id]])) {
						$checkImages = $this->checkImages($productData, $productsCrm[$this->mapCrm['product'][$id]]);
						$hasChanges = $this->hasChanges($productsCrm[$this->mapCrm['product'][$id]], $productData);
						if (!empty($checkImages) || $hasChanges) {
							if (self::MAGENTO === $this->whichToUpdate($productsCrm[$this->mapCrm['product'][$id]], $productData)) {
								if ($hasChanges) {
									$this->updateProduct($id, $productsCrm[$this->mapCrm['product'][$id]]);
								}
								if (!empty($checkImages['add']) || !empty($checkImages['remove'])) {
									$this->updateImages($product['sku'], $checkImages);
								}
							} else {
								if ($hasChanges) {
									$this->updateProductCrm($this->mapCrm['product'][$id], $productData);
								}
								$this->updateImagesCrm($this->mapCrm['product'][$id], $checkImages['addCrm'] ?? []);
							}
						}
						if ('grouped' === $productData['type_id']) {
							$this->updateBundleProductsCrm($this->mapCrm['product'][$id], $productData['product_links'], $productsCrm[$this->mapCrm['product'][$id]]['related']);
						}
					} elseif (isset($this->mapCrm['product'][$id]) && !isset($productsCrm[$this->mapCrm['product'][$id]])) {
						$this->deleteProduct($id);
					} else {
						$this->saveProductCrm($productData);
					}
					$this->config->setScan('product', 'id', $id);
				}
			} else {
				$allChecked = true;
			}
		} catch (\Throwable $ex) {
			\App\Log::error('Error during saving magento products to yetiforce: ' . $ex->getMessage(), 'Integrations/Magento');
			$allChecked = false;
		}
		return $allChecked;
	}

	/**
	 * Method to delete removed products from YetiForce and Magento.
	 *
	 * @return bool
	 */
	public function checkProductsMap(): bool
	{
		$allChecked = false;
		try {
			if (!empty($this->mapKeys['product'])) {
				$productsCrm = $this->getProductsCrm('all');
				if (!empty($productsCrm) && $diffedRecords = \array_diff_key($this->map['product'], $productsCrm)) {
					foreach ($diffedRecords as $idCrm => $id) {
						$this->deleteProduct($id);
					}
				}
				$products = $this->getProducts('all');
				if (!empty($products) && $diffedRecordsCrm = \array_diff_key($this->mapCrm['product'], $products)) {
					foreach ($diffedRecordsCrm as $id => $idCrm) {
						$this->deleteProductCrm($idCrm);
					}
				}
			}
			$allChecked = true;
		} catch (\Throwable $ex) {
			\App\Log::error('Error during checking products map: ' . $ex->getMessage(), 'Integrations/Magento');
		}
		return $allChecked;
	}

	/**
	 * Method to get products form YetiForce.
	 *
	 * @param string|array $ids
	 *
	 * @throws \App\Exceptions\NotAllowedMethod
	 * @throws \ReflectionException
	 *
	 * @return array
	 */
	public function getProductsCrm($ids = []): array
	{
		$query = (new \App\QueryGenerator('Products'))->createQuery();
		$productsCrm = [];
		if (!empty($ids)) {
			if ('all' !== $ids) {
				$query->andWhere(['IN', 'productid', $ids]);
			}
		} else {
			$query->andWhere(['>', 'productid', $this->lastScan['idcrm']]);
			$query->andWhere(['<=', 'modifiedtime', $this->lastScan['start_date']]);
			if (!empty($this->lastScan['end_date'])) {
				$query->andWhere(['>=', 'modifiedtime', $this->lastScan['end_date']]);
			}
			$query->limit($this->config->get('productLimit'));
		}
		$dataReader = $query->createCommand()->query();
		while ($row = $dataReader->read()) {
			$row['related'] = $this->getRelatedProductsCrm($row['productid']);
			$productsCrm[$row['productid']] = $row;
		}
		return $productsCrm;
	}

	/**
	 * Method to get related products of given product id.
	 *
	 * @param $id
	 *
	 * @throws \App\Exceptions\NotAllowedMethod
	 *
	 * @return array
	 */
	public function getRelatedProductsCrm($id): array
	{
		$parentRecordModel = \Vtiger_Record_Model::getInstanceById($id, 'Products');
		$relationModel = \Vtiger_Relation_Model::getInstance($parentRecordModel->getModule(), $parentRecordModel->getModule())->set('parentRecord', $parentRecordModel)->set('query_generator', null);
		return $relationModel->getQuery()->setFields(['id'])->createQuery()->column();
	}

	/**
	 * Method to save product to YetiForce.
	 *
	 * @param array $data
	 *
	 * @return int
	 */
	public function saveProductCrm(array $data): int
	{
		$className = $this->config->get('productMapClassName');
		$productFields = new $className();
		$productFields->setData($data);
		$dataCrm = $productFields->getDataCrm();
		$value = 0;
		if (!empty($dataCrm)) {
			try {
				$recordModel = \Vtiger_Record_Model::getCleanInstance('Products');
				$recordModel->setData($dataCrm);
				$this->saveImagesCrm($recordModel, $data['media_gallery_entries']);
				$recordModel->save();
				$this->saveMapping($data['id'], $recordModel->getId(), 'product');
				$this->saveStorage($recordModel->getId(), $recordModel->get('qtyinstock') ?? 0);
				$this->saveBundleProductsCrm($recordModel, $data['product_links']);
				$value = $recordModel->getId();
			} catch (\Throwable $ex) {
				\App\Log::error('Error during saving yetiforce product: ' . $ex->getMessage(), 'Integrations/Magento');
			}
		}
		return $value;
	}

	/**
	 * Update bundle products.
	 *
	 * @param $idCrm
	 * @param $bundleProducts
	 * @param $bundleProductsCrm
	 *
	 * @throws \Exception
	 */
	public function updateBundleProductsCrm($idCrm, $bundleProducts, $bundleProductsCrm): void
	{
		$saveProducts = [];
		$recordModel = \Vtiger_Record_Model::getInstanceById($idCrm, 'Products');
		foreach ($bundleProducts as $bundleProduct) {
			$id = $this->mapSkuToId[$bundleProduct['linked_product_sku']];
			if (!isset($this->mapCrm['product'][$id]) || !\in_array($this->mapCrm['product'][$id], $bundleProductsCrm)) {
				$saveProducts[] = $bundleProduct;
			}
			unset($bundleProductsCrm[array_search($this->mapCrm['product'][$id], $bundleProductsCrm)]);
		}
		$this->saveBundleProductsCrm($recordModel, $saveProducts);
		$this->deleteBundleProductsCrm($recordModel, $bundleProductsCrm);
	}

	/**
	 * Save bundle products.
	 *
	 * @param $recordModel
	 * @param $products
	 *
	 * @throws \Exception
	 */
	public function saveBundleProductsCrm($recordModel, $products): void
	{
		if (!empty($products)) {
			$relationModel = \Vtiger_Relation_Model::getInstance($recordModel->getModule(), $recordModel->getModule());
			foreach ($products as $product) {
				if ('associated' === $product['link_type']) {
					$productId = $this->mapSkuToId[$product['linked_product_sku']];
					if (isset($this->mapCrm['product'][$productId])) {
						$relationModel->addRelation($recordModel->getId(), $this->mapCrm['product'][$productId]);
					} else {
						$productIdCrm = $this->saveProductCrm($this->getProductFullData($product['linked_product_sku']));
						if ($productIdCrm) {
							$relationModel->addRelation($recordModel->getId(), $productIdCrm);
						}
					}
				}
			}
		}
	}

	/**
	 * Delete bundle products.
	 *
	 * @param $recordModel
	 * @param $products
	 */
	public function deleteBundleProductsCrm($recordModel, $products): void
	{
		if (!empty($products)) {
			$relationModel = \Vtiger_Relation_Model::getInstance($recordModel->getModule(), $recordModel->getModule());
			foreach ($products as $product) {
				$relationModel->deleteRelation($recordModel->getId(), $product);
			}
		}
	}

	/**
	 * Method to update product in YetiForce.
	 *
	 * @param int   $id
	 * @param array $data
	 *
	 * @throws \Exception
	 */
	public function updateProductCrm(int $id, array $data): void
	{
		try {
			$className = $this->config->get('productMapClassName');
			$productFields = new $className();
			$productFields->setData($data);
			$recordModel = \Vtiger_Record_Model::getInstanceById($id, 'Products');
			foreach ($productFields->getDataCrm(true) as $key => $value) {
				$recordModel->set($key, $value);
			}
			$recordModel->save();
			$this->saveStorage($recordModel->getId(), $recordModel->get('qtyinstock') ?? 0);
		} catch (\Throwable $ex) {
			\App\Log::error('Error during updating yetiforce product: ' . $ex->getMessage(), 'Integrations/Magento');
		}
	}

	/**
	 * Save images in YetiForce.
	 *
	 * @param $recordModel
	 * @param $images
	 *
	 * @throws \App\Exceptions\AppException
	 * @throws \ReflectionException
	 */
	public function saveImagesCrm(&$recordModel, $images): void
	{
		$imagePath = $this->config->get('addressApi') . $this->config->get('productImagesPath');
		$imagesData = [];
		if (!empty($images)) {
			foreach ($images as $image) {
				if (isset($image['file'])) {
					$url = $imagePath . $image['file'];
					try {
						if ($fileInstance = \App\Fields\File::saveImageFromUrl($url, 'Products', 'image')) {
							$imagesData[] = [
								'name' => $fileInstance['name'],
								'size' => $fileInstance['size'],
								'key' => $fileInstance['key'],
								'path' => $fileInstance['path']
							];
						}
					} catch (\Exception $ex) {
						\App\Log::error('Error during saving product image in yetiforce: ' . $ex->getMessage(), 'Integrations/Magento');
					}
				} else {
					$imagesData[] = $image;
				}
			}
		}
		$recordModel->set('imagename', \App\Json::encode($imagesData));
	}

	/**
	 * Update images in YetiForce.
	 *
	 * @param $id
	 * @param $images
	 */
	public function updateImagesCrm($id, $images): void
	{
		try {
			$recordModel = \Vtiger_Record_Model::getInstanceById($id, 'Products');
			$this->saveImagesCrm($recordModel, $images);
			$recordModel->save();
		} catch (\Throwable $ex) {
			\App\Log::error('Error during updating yetiforce images product: ' . $ex->getMessage(), 'Integrations/Magento');
		}
	}

	/**
	 * Method to delete product in YetiForce.
	 *
	 * @param int $id
	 *
	 * @return bool
	 */
	public function deleteProductCrm(int $id): bool
	{
		try {
			$recordModel = \Vtiger_Record_Model::getInstanceById($id, 'Products');
			$recordModel->delete();
			$this->deleteMapping($this->map['product'][$id], $id, 'product');
			$result = true;
		} catch (\Throwable $ex) {
			\App\Log::error('Error during deleting yetiforce product: ' . $ex->getMessage(), 'Integrations/Magento');
			$result = false;
		}
		return $result;
	}

	/**
	 * Save product storage data.
	 *
	 * @param int   $productId
	 * @param float $value
	 *
	 * @throws \ReflectionException
	 * @throws \yii\db\Exception
	 */
	public function saveStorage(int $productId, float $value): void
	{
		$storageId = $this->config->get('storageId');
		if (!empty($storageId)) {
			$db = \App\Db::getInstance();
			$referenceInfo = \Vtiger_Relation_Model::getReferenceTableInfo('Products', 'IStorages');
			if ((new \App\Db\Query())->select([$referenceInfo['rel'], 'qtyinstock'])
				->from($referenceInfo['table'])
				->where([$referenceInfo['base'] => $storageId, $referenceInfo['rel'] => $productId])
				->exists()) {
				$db->createCommand()->update($referenceInfo['table'], [
					'qtyinstock' => $value,
				], [$referenceInfo['base'] => $storageId, $referenceInfo['rel'] => $productId])->execute();
			} else {
				$db->createCommand()->insert($referenceInfo['table'], [
					$referenceInfo['base'] => $storageId,
					$referenceInfo['rel'] => $productId,
					'qtyinstock' => $value,
				])->execute();
			}
		}
	}

	/**
	 * Mapped magento id to sku.
	 *
	 * @var array
	 */
	public $mapIdToSku = [];
	/**
	 * Mapped magento sku to id.
	 *
	 * @var array
	 */
	public $mapSkuToId = [];

	/**
	 * Method to update product in Magento.
	 *
	 * @param int   $productId
	 * @param array $product
	 *
	 * @return bool
	 */
	public function updateProduct(int $productId, array $product): bool
	{
		$result = false;
		if (!empty($this->mapIdToSku[$productId])) {
			try {
				$className = $this->config->get('productMapClassName');
				$productFields = new $className();
				$productFields->setDataCrm($product);
				$this->connector->request('PUT', $this->config->get('storeCode') . '/V1/products/' . urlencode($this->mapIdToSku[$productId]), $productFields->getData(true));
				$result = true;
			} catch (\Throwable $ex) {
				\App\Log::error('Error during updating magento product: ' . $ex->getMessage(), 'Integrations/Magento');
			}
		}
		return $result;
	}

	/**
	 * Method to save product to Magento.
	 *
	 * @param array $product
	 *
	 * @throws \ReflectionException
	 *
	 * @return bool
	 */
	public function saveProduct(array $product): bool
	{
		$className = $this->config->get('productMapClassName');
		$productFields = new $className();
		$productFields->setDataCrm($product);
		try {
			$productRequest = \App\Json::decode($this->connector->request('POST', $this->config->get('storeCode') . '/V1/products/', $productFields->getData()));
			if (!empty($productRequest)) {
				$this->saveImages($productRequest['sku'], \App\Json::decode($product['imagename']));
				$this->saveMapping($productRequest['id'], $product['productid'], 'product');
			} else {
				\App\Log::error('Error during saving magento product: empty product request', 'Integrations/Magento');
			}
			$result = true;
		} catch (\Throwable $ex) {
			\App\Log::error('Error during saving magento product: ' . $ex->getMessage(), 'Integrations/Magento');
			$result = false;
		}
		return $result;
	}

	/**
	 * Method to delete product in Magento.
	 *
	 * @param int $productId
	 *
	 * @return bool
	 */
	public function deleteProduct(int $productId): bool
	{
		try {
			if (!empty($this->mapIdToSku)) {
				if (isset($this->mapIdToSku[$productId])) {
					$this->connector->request('DELETE', 'all/V1/products/' . urlencode($this->mapIdToSku[$productId]), []);
				}
				$this->deleteMapping($productId, $this->mapCrm['product'][$productId], 'product');
			}
			$result = true;
		} catch (\Throwable $ex) {
			\App\Log::error('Error during deleting magento product: ' . $ex->getMessage(), 'Integrations/Magento');
			$result = false;
		}
		return $result;
	}

	/**
	 * Method to get sku mapped by product id.
	 *
	 * @throws \App\Exceptions\AppException
	 * @throws \ReflectionException
	 */
	public function getProductSkuMap(): void
	{
		if (empty($this->mapIdToSku)) {
			$page = 1;
			do {
				$data = \App\Json::decode($this->connector->request('GET', $this->config->get('storeCode') . '/V1/products?fields=items[id,sku]&searchCriteria[pageSize]=1000&searchCriteria[currentPage]=' . $page));
				if (!empty($data['items'])) {
					foreach ($data['items'] as $item) {
						$this->mapIdToSku[$item['id']] = $item['sku'];
					}
				}
				++$page;
			} while (!(\count($data['items']) < 1000));
			if (!empty($this->mapIdToSku)) {
				$this->mapSkuToId = \array_flip($this->mapIdToSku);
			}
		}
	}

	/**
	 * Method to get products form Magento.
	 *
	 * @param string|array $ids
	 *
	 * @throws \App\Exceptions\AppException
	 * @throws \ReflectionException
	 *
	 * @return array
	 */
	public function getProducts($ids = ''): array
	{
		$items = [];
		$data = \App\Json::decode($this->connector->request('GET', $this->config->get('storeCode') . '/V1/products?' . $this->getSearchCriteria($ids, $this->config->get('productLimit'))));
		if (!empty($data['items'])) {
			foreach ($data['items'] as $item) {
				$items[$item['id']] = $item;
			}
		}
		return $items;
	}

	/**
	 * Get full product data.
	 *
	 * @param string $sku
	 *
	 * @return array|mixed
	 */
	public function getProductFullData(string $sku)
	{
		$data = [];
		try {
			$data = \App\Json::decode($this->connector->request('GET', $this->config->get('storeCode') . '/V1/products/' . urlencode($sku)));
		} catch (\Throwable $ex) {
			\App\Log::error('Error during getting magento product data: ' . $ex->getMessage(), 'Integrations/Magento');
		}
		return $data;
	}

	/**
	 * Method to get search criteria Magento products.
	 *
	 * @param array $ids
	 * @param int   $pageSize
	 *
	 * @return string
	 */
	public function getSearchCriteria($ids, int $pageSize = 10): string
	{
		return parent::getSearchCriteria($ids, $pageSize) . '&fields=items[id,sku]';
	}

	/**
	 * Update product images.
	 *
	 * @param $sku
	 * @param $imagesData
	 */
	public function updateImages($sku, $imagesData)
	{
		if (!empty($imagesData['add'])) {
			$this->saveImages($sku, $imagesData['add']);
		}
		if (!empty($imagesData['remove'])) {
			$this->removeImages($sku, $imagesData['remove']);
		}
	}

	/**
	 * Save product images.
	 *
	 * @param $sku
	 * @param $images
	 */
	public function saveImages($sku, $images)
	{
		if (!empty($images)) {
			foreach ($images as $image) {
				$imageBaseData = \App\Fields\File::getImageBaseData($image['path']);
				$imageBaseData = explode(',', $imageBaseData);
				$imageType = str_replace([';base64', 'data:'], '', $imageBaseData[0]);
				$data = [
					'entry' => [
						'media_type' => 'image',
						'label' => 'Image',
						'disabled' => false,
						'types' => [
							'image',
							'small_image',
							'thumbnail'
						],
						'content' => [
							'base64_encoded_data' => $imageBaseData[1],
							'type' => $imageType,
							'name' => $image['name']
						],
					]
				];
				try {
					\App\Json::decode($this->connector->request('POST', 'V1/products/' . urlencode($sku) . '/media',
						$data
					));
				} catch (\Throwable $ex) {
					\App\Log::error('Error during saving magento product images: ' . $ex->getMessage(), 'Integrations/Magento');
				}
			}
		}
	}

	/**
	 * Remove product images.
	 *
	 * @param $sku
	 * @param $images
	 */
	public function removeImages($sku, $images)
	{
		if (!empty($images)) {
			foreach ($images as $image) {
				try {
					\App\Json::decode($this->connector->request('DELETE', 'V1/products/' . urlencode($sku) . "/media/{$image['id']}"));
				} catch (\Throwable $ex) {
					\App\Log::error('Error during removing magento product image: ' . $ex->getMessage(), 'Integrations/Magento');
				}
			}
		}
	}
}
