<?php

namespace App\Controller\Items;

use App\Controller\BaseController;
use App\Model\Customer;
use Exception;
use Pimcore\Db;
use Pimcore\Model\Asset;
use Pimcore\Model\Asset\Folder as AssetFolder;
use Pimcore\Model\Asset\Image;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Model\DataObject\ClassDefinition\Data\Fieldcollections;
use Pimcore\Model\DataObject\ClassDefinition\Data\Hotspotimage;
use Pimcore\Model\DataObject\ClassDefinition\Data\ManyToManyObjectRelation;
use Pimcore\Model\DataObject\ClassDefinition\Data\ManyToManyRelation;
use Pimcore\Model\DataObject\ClassDefinition\Data\ManyToOneRelation;
use Pimcore\Model\DataObject\ClassDefinition\Data\Select;
use Pimcore\Model\DataObject\Data\Hotspotimage as DataHotspotimage;
use Pimcore\Model\DataObject\Folder;
use Pimcore\Model\DataObject\Manufacturer;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\ProductPrice;
use Pimcore\Model\DataObject\Supplier;
use Pimcore\Model\DataObject\Tags;
use Pimcore\Model\DataObject\Warehouse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Pimcore\Model\Element\DuplicateFullPathException;
use function PHPUnit\Framework\isNull;

class ItemsControlller extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/items/items', name: 'items')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function itemsAction(Request $request): Response
    {
        $this->checkPermission($this->getUser(), ["wegepate"]);

        $searchTerm = $request->query->get("search");

        // filters
        $manufacturer = $request->query->get("manufacturer");
        $warehouse = $request->query->get("warehouse");
        $tag = $request->query->get("tag");

        // sort
        $sort = $request->query->get("sort");

        $page = max(1, $request->query->getInt("page"));
        $limit =10;
        $offset = ($page - 1) * $limit;

        $products = [];
        $allProducts = Product::getList();
        $db = Db::get();
        
        if(!empty($searchTerm)){
            $allProducts->setCondition("Itemname LIKE ?", ["%$searchTerm%"]);
        }
        if(!empty($manufacturer)){
            $allProducts->addConditionParam("Manufacturer__id = ?", ["$manufacturer"]);
        }
        // if(!empty($warehouse)){
            
        // }
        if(!empty($tag)) {
            $allProducts->addConditionParam("tags LIKE ?", ["%$tag%"]);
        }


        // Sorting
        if (in_array($sort, ['inventory_asc', 'inventory_desc'])) {
            $order = ($sort === 'inventory_asc') ? 'ASC' : 'DESC';
        
            // Fetch product IDs sorted by total inventory
            $query = "
                SELECT p.oo_id, COALESCE(SUM(pp.amount), 0) AS total_inventory
                FROM object_product AS p
                LEFT JOIN object_productprice AS pp ON pp.product__id = p.oo_id
                GROUP BY p.oo_id
                ORDER BY total_inventory $order;
            ";
            $sortedProductIds = array_column($db->fetchAllAssociative($query), 'oo_id');
        
            // Fetch products normally
            $allProductsArray = iterator_to_array($allProducts);
            
            // Map products by ID for reordering
            $productsById = [];
            foreach ($allProductsArray as $product) {
                $productsById[$product->getId()] = $product;
            }
        
            // Reorder the products based on sortedProductIds
            $sortedProducts = [];
            foreach ($sortedProductIds as $id) {
                if (isset($productsById[$id])) {
                    $sortedProducts[] = $productsById[$id];
                }
            }
        
            $allProducts = $sortedProducts;
        } else {
            // Apply normal sorting
            switch ($sort) {
                case 'price_asc':
                    $allProducts->setOrderKey("price")->setOrder("ASC");
                    break;
                case 'price_desc':
                    $allProducts->setOrderKey("price")->setOrder("DESC");
                    break;
            }
        }

        $totalProducts = count($allProducts);
        $totalPages = max(1, ceil($totalProducts / $limit));

        if((in_array($sort, ['inventory_asc', 'inventory_desc']))) {
            $allProducts = array_slice($allProducts, $offset, $limit);
        } else {
            $allProducts->setLimit($limit)->setOffset($offset);
        }

        // fields for populating on the items page
        foreach($allProducts as $item) {
            $tags = [];
            $fetchedTags = $item->getTags();
            foreach($fetchedTags as $tag) {
                $tags[] = $tag->getName();
            }

            $firstImage = "";
            $imageGallery = $item->getImages();
            if($imageGallery && count($imageGallery->getItems()) > 0) {
                $firstImage = $imageGallery->getItems()[0]->getImage()->getFullPath();
            }

            $compatibleProduct = null;
            $compatibleProducts = $item->getCompatibleProducts();
            if(count($compatibleProducts) > 0 ) {
                $compatibleProduct = [
                    "id" => $item->getCompatibleProducts()[0]->getId(),
                    "name" => $item->getCompatibleProducts()[0]->getItemname()
                ];
            }

            $product = [
                "id" => $item->getId(),
                "itemno" => $item->getItemNo(),
                "name" => $item->getItemname(),
                "image" => $firstImage,
                "description" => $item->getShortDescription(),
                "tags" => $tags,
                "compatible_product" => $compatibleProduct,
                "itemType" => $item->getItemType(),
                "inventories" => $item->getInventories()
            ];

            $products[] = $product;
        }

        // Warehouses
        $warehouseListing = Warehouse::getList();
        $warehouses = [];
        foreach($warehouseListing as $warehouse) {
            $warehouses[] = [
                "id" => $warehouse->getId(),
                "name" => $warehouse->getName()
            ];
        }
        
        // Manufacturers
        $manufacturerListing = Manufacturer::getList();
        $manufacturers = [];
        foreach($manufacturerListing as $manufacturer) {
            $manufacturers[] = [
                "id" => $manufacturer->getId(),
                "name" => $manufacturer->getName()
            ];
        }

        // Tags
        $tagListing = Tags::getList();
        $tags = [];
        foreach($tagListing as $tag) {
            $tags[] = [
                "id" => $tag->getId(),
                "name" => $tag->getName()
            ];
        }

        // var_dump($products);

        return $this->render('items/items.html.twig', [
            "products" => $products,
            "searchTerm" => $searchTerm,
            "currentPage" => $page,
            "totalProducts" => $totalProducts,
            "totalPages" => $totalPages,
            "warehouses" => $warehouses,
            "manufacturers" => $manufacturers,
            "tags" => $tags
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/items/items-create/{id?}', name: 'items-create', methods: ['GET', 'POST'])]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createItemsAction(Request $request, ?int $id = null): Response
    {
        try {
            if ($request->isMethod('POST')) {
                $formData = $request->request->all();
                
                if ($id) {
                    // echo "existing product";
                    $product = Product::getById($id);
                    if (!$product) {
                        throw new \Exception("Product not found.");
                    }
                } else {
                    // echo "new product";
                    $product = new Product();
                    $product->setPath("/Products");
                    $productName = $request->get("Itemname");
                    $validKey  = \Pimcore\Model\Element\Service::getValidKey($productName,'object');
                    if($validKey) {
                        $product->setKey($validKey);
                    }
                    $productsFolder = Folder::getByPath("/Products");
                    $product->setParentId($productsFolder->getId());
                }
                
                foreach ($formData as $key => $item) {
                    // echo $key . "<br>";
                    // var_dump($item);
                    // echo "<br>";
                    $field = $product->getClass()->getFieldDefinition($key);
                    if (!$field) {
                        continue;
                    }
                    $fieldType = $field->getFieldType();
                    // echo $fieldType;
                    // echo "<br><br>";
                    $setterMethod = "set" . ucfirst($key);
                    
                    // Handle field collections for suppliers separately.
                    if ($fieldType === "fieldcollections" && $key === "suppliers" && !empty($item)) {
                        $fieldCollection = new \Pimcore\Model\DataObject\Fieldcollection();
                        
                        foreach ($item as $subKey => $supplierData) {
                            // echo "<br>inside looping supplier data and id: ".$subKey;
                            $supplierItem = new \Pimcore\Model\DataObject\Fieldcollection\Data\SuppliersFieldCollection();
                            $supplier = Supplier::getById($subKey);
                            $supplierItem->setSupplier($supplier);
                            $supplierItem->setSupplierNETprice($supplierData['supplierNETprice']);
                            $supplierItem->setSupplierItemID($supplierData['supplierItemID']);
                            $supplierItem->setTypicalDeliveryTime($supplierData['TypicalDeliveryTime']);

                            
                            $fieldCollection->add($supplierItem);

                        }
                        $product->$setterMethod($fieldCollection);
                    }
                    // Process relation fields.
                    elseif ($fieldType === "manyToOneRelation" || $fieldType === "manyToManyObjectRelation") {
                        $fieldClasses = $field->getClasses();
                        $objectClass = $fieldClasses[0]["classes"];
                        $fqClassName = "\\Pimcore\\Model\\DataObject\\" . $objectClass;
                        
                        if ($fieldType === "manyToOneRelation") {
                            $object = $fqClassName::getById($item);
                            $product->$setterMethod($object);
                        } elseif ($fieldType === "manyToManyObjectRelation") {
                            $objects = [];
                            foreach ($item as $idItem) {
                                $obj = $fqClassName::getById($idItem);
                                if ($obj) {
                                    $objects[] = $obj;
                                }
                            }
                            $product->$setterMethod($objects);
                        }
                    }
                    // Skip reverse object relation and imageGallery fields.
                    elseif (in_array($fieldType, ["reverseObjectRelation", "imageGallery"])) {
                        continue;
                    }
                    // Default handling for regular fields.
                    else {
                        if (!empty($item)) {
                            $product->$setterMethod($item);
                        }
                    }
                }
                
                // Handle images upload.
                if (!empty($request->get('images'))) {
                    $assetIds = $request->get('images');
                    $imageGallery = new \Pimcore\Model\DataObject\Data\ImageGallery();
                    $galleryItems = [];
                
                    foreach ($assetIds as $assetId) {
                        $image = \Pimcore\Model\Asset\Image::getById($assetId);
                        if ($image instanceof \Pimcore\Model\Asset\Image) {
                            // Wrap the asset into a Hotspotimage for ImageGallery.
                            $hotspotImage = new \Pimcore\Model\DataObject\Data\Hotspotimage();
                            $hotspotImage->setImage($image);
                            $galleryItems[] = $hotspotImage;
                        }
                    }
                
                    $imageGallery->setItems($galleryItems);
                    $product->setImages($imageGallery);
                }
                
                $product->setPublished(true);
                $product->save();
                
                // Process inventory entries (reverse object relation field)
                if (!empty($request->get("inventories"))) {
                    $inventories = $request->get("inventories");
                    foreach ($inventories as $inventory) {
                        // If the form is an edit form
                        if(!empty($inventory['id'])) {
                            $productPrice = ProductPrice::getById($inventory['id']);
                        } else {
                            $productPrice = new ProductPrice();
                        }
                        $warehouse = \Pimcore\Model\DataObject\Warehouse::getById($inventory['Location']);
                        $productPrice->setKey($product->getItemNo() . "-" . $warehouse->getName());
                        $productPricesFolder = Folder::getByPath("/ProductPrices");
                        $productPrice->setParentId($productPricesFolder->getId());
                        
                        $productPrice->setProduct($product);
                        $productPrice->setLocation($warehouse);
                        $productPrice->setAmount($inventory['Amount']);
                        $productPrice->setComment($inventory['Comment']);
                        
                        $productPrice->setPublished(true);
                        $productPrice->save();
                    }
                }
                
                $this->addFlash('success', 'Product saved successfully!');
                if($id){
                    return $this->redirectToRoute('items-create', ['id' => $id]);
                }else{
                    return $this->redirectToRoute('items-create',['id' => $product->getId()]);
                }
               
            } else {
                $dropdownFields = [];
                $addedKeys = []; // Tracking added dropdown field names to prevent duplicates
            
                $product = new Product();
                $productClass = \Pimcore\Model\DataObject\ClassDefinition::getByName("Product");
                $productFields = $productClass->getFieldDefinitions();
            
                foreach ($productFields as $field) {
                    if (in_array(get_class($field), [
                        ManyToOneRelation::class,
                        ManyToManyObjectRelation::class,
                        Select::class,
                        Fieldcollections::class
                    ])) {
                        // Handling Select Fields
                        if ($field instanceof Select) {
                            $fieldName = $field->getName();
                            if (!isset($addedKeys[$fieldName])) {
                                if ($field->getOptionsProviderData()) {
                                    $options = \Pimcore\Model\DataObject\Service::getOptionsForSelectField($product, $fieldName);
                                    $options = array_keys($options);
                                } else {
                                    $options = array_column($field->getOptions(), "key");
                                }
                                $dropdownFields[] = [
                                    $fieldName => $options ?? []
                                ];
                                $addedKeys[$fieldName] = true;
                            }
                        }
            
                        // Handling Relations (ManyToOne / ManyToMany)
                        if (in_array(get_class($field), [ManyToOneRelation::class, ManyToManyObjectRelation::class])) {
                            $allowedClasses = $field->getClasses();
                            foreach ($allowedClasses as $className) {
                                $classType = $className["classes"];
                                if (!isset($addedKeys[$classType])) {
                                    $fetchedClass = "Pimcore\\Model\\DataObject\\" . $classType;
                                    if (class_exists($fetchedClass)) {
                                        $listClass = $fetchedClass . "\\Listing";
                                        $objectList = new $listClass();
                                        $objects = $objectList->load();
                                        $objectNames = [];
            
                                        foreach ($objects as $object) {
                                            $objectId = $object->getId();
                                            $objectName = ($classType == "Product") ? 
                                                ($object->getItemname() ?: $object->getKey()) : 
                                                ($object->getName() ?: $object->getKey());
            
                                            $objectNames[] = [
                                                $objectId => $objectName
                                            ];
                                        }
            
                                        $dropdownFields[] = [
                                            $classType => $objectNames
                                        ];
                                        $addedKeys[$classType] = true;
                                    }
                                }
                            }
                        }
            
                        // Handling Fieldcollections (Suppliers)
                        if ($field instanceof Fieldcollections && $field->getName() == "suppliers") {
                            if (!isset($addedKeys["Suppliers"])) {
                                $suppliers = Supplier::getList();
                                $supplierData = [];
            
                                foreach ($suppliers as $supplier) {
                                    $supplierData[$supplier->getId()] = $supplier->getName();
                                }
            
                                $dropdownFields[] = [
                                    "Suppliers" => $supplierData
                                ];
                                $addedKeys["Suppliers"] = true;
                            }
                        }
                    }
                }

                // Item set compatible products
                $itemSetCompatible = [];
                $productListing = Product::getList();
                $productListing->setCondition("itemType != ?", "Item-Set");
                
                foreach($productListing as $product) {
                    $productId = $product->getId();
                    $productName = $product->getItemname();
                    $itemSetCompatible[] = [
                        $productId => $productName
                    ];
                }

                $dropdownFields[] = [
                    "Item-set" => $itemSetCompatible
                ];
            
                // Handling Warehouses
                if (!isset($addedKeys["Warehouses"])) {
                    $warehouses = \Pimcore\Model\DataObject\Warehouse::getList();
                    $warehouseData = [];
            
                    foreach ($warehouses as $warehouse) {
                        $warehouseData[$warehouse->getId()] = $warehouse->getName();
                    }
            
                    $dropdownFields[] = [
                        "Warehouses" => $warehouseData
                    ];
                    $addedKeys["Warehouses"] = true;
                }
            
                if ($id) {
                    $product = Product::getById($id);
                }
            
                return $this->render('items/items_create.html.twig', [
                    "dropdownFields" => $dropdownFields,
                    "editMode"  => ($id !== null),
                    "product"   => $id ? $product : null
                ]);
            }
            
            
        } catch (\Exception $e) {
            if($e instanceof DuplicateFullPathException){
                $this->addFlash('error', 'Product with the same name already exists');
            }else{  
                $this->addFlash('error', 'Error saving product: ' . $e->getMessage());
            }

            if($id){
                return $this->redirectToRoute('items-create', ['id' => $id]);
            }else{
                if($product && $product->getId()){
                    return $this->redirectToRoute('items-create',['id' => $product->getId()]);
                }else{
                    return $this->redirectToRoute('items-create');
                }
             
            }

            
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/items/item-info', name: 'items-item-info', methods: ['GET', 'POST'])]
    #[IsGranted("IS_AUTHENTICATED")]
    public function itemInfoAction(Request $request): JsonResponse
    {
        if($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);
            $productId = $data["productId"];
            $product = Product::getById($productId);
            if ($product) {
                $productName = $product->getItemname();
                $productShortDes = $product->getShortDescription();
                $productImages = $product->getImages()->getItems();
                $productImg = "";
                if (!empty($productImages)) {
                    $productImg = $productImages[0]->getImage();
                    $productImg = $productImg->getFullPath();
                }
                return new JsonResponse([
                    "id" => $productId,
                    "name" => $productName,
                    "shortDescription" => $productShortDes,
                    "image" => $productImg
                ]);
            } else {
                return new Response('Product not found', 404);
            }
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/items/supplier-info', name: 'items-supplier-info', methods: ['GET', 'POST'])]
    #[IsGranted("IS_AUTHENTICATED")]
    public function supplierInfoAction(Request $request): JsonResponse
    {
        if($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);
            $supplierId = $data["supplierId"];
            $supplier = Supplier::getById($supplierId);
            if ($supplier) {
                $supplierName = $supplier->getName();

                return new JsonResponse([
                    "id" => $supplierId,
                    "name" => $supplierName,
                ]);
            } else {
                return new Response('Supplier not found', 404);
            }
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/items/image/upload', name: 'items-image-upload', methods: ['GET', 'POST'])]
    #[IsGranted("IS_AUTHENTICATED")]
    public function imageUploadAction(Request $request): JsonResponse
    {
        $files = $request->files->all()['images'] ?? null;
        if (!$files) {
            return new JsonResponse(['error' => 'No files provided'], 400);
        }
        
        $results = [];
        try {
            $folder = AssetFolder::getByPath("/product-images");
            if (!$folder) {
                $folder = new AssetFolder();
                $folder->setParentId(1);
                $folder->setKey("product-images");
                $folder->save();
            }
            
            foreach ($files as $file) {
                if (!$file->isValid()) {
                    continue;
                }
                
                $originalFilename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $baseName = pathinfo($originalFilename, PATHINFO_FILENAME);
                $uniqueFilename = $baseName . '-' . time() . '-' . rand(1000, 9999) . '.' . $extension;
                
                $image = new Image();
                $image->setParent($folder);
                $image->setFilename($uniqueFilename);
                $image->setData(file_get_contents($file->getPathname()));
                $image->save();
                
                $hotspotImage = new \Pimcore\Model\DataObject\Data\Hotspotimage();
                $hotspotImage->setImage($image);
                
                $results[] = [
                    'id' => $image->getId(),
                    'fullPath' => $image->getFullPath()
                ];
            }
            
            return new JsonResponse($results);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    #[Route('/items/image/delete', name: 'items-image-delete', methods: ['POST'])]
    #[IsGranted("IS_AUTHENTICATED")]
    public function imageDeleteAction(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $assetId = $data['id'];

        if (!$assetId) {
            return new JsonResponse(['error' => 'Invalid asset ID'], 400);
        }

        $asset = Image::getById((int) $assetId);
        if (!$asset) {
            return new JsonResponse(['error' => 'Asset not found'], 404);
        }

        try {
            $asset->delete();
            return new JsonResponse(['message' => 'Asset deleted successfully']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to delete asset: ' . $e->getMessage()], 500);
        }
    }

    #[Route('/items/inventory/delete', name: 'items-inventory-delete', methods: ['POST'])]
    #[IsGranted("IS_AUTHENTICATED")]
    public function imageInventoryAction(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['inventoryId']) || empty($data['inventoryId'])) {
            return new JsonResponse(['success' => false, 'message' => 'Invalid inventory ID'], 400);
        }

        $inventoryId = (int) $data['inventoryId'];
        $inventory = ProductPrice::getById($inventoryId);

        if (!$inventory) {
            return new JsonResponse(['success' => false, 'message' => 'Inventory item not found'], 404);
        }

        try {
            $inventory->delete();
            return new JsonResponse(['success' => true, 'message' => 'Inventory deleted successfully']);
        } catch (\Exception $e) {
            return new JsonResponse(['success' => false, 'message' => 'Failed to delete inventory: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @Route("/delete-product/{id}", methods={"DELETE"})
     */
    public function deleteProductAction($id, Request $request): JsonResponse
    {
        $product = Product::getById($id);

        if (!$product) {
            return new JsonResponse(["success" => false, "message" => "Product not found"], 404);
        }

        try {
            $product->delete();
            return new JsonResponse(["success" => true, "message" => "Product deleted successfully"]);
        } catch (\Exception $e) {
            return new JsonResponse(["success" => false, "message" => "Error deleting product"], 500);
        }
    }

    /**
     * @param Request $request
     * @param int $artikelid
     * @return Response
     */
    #[Route('/items/items-edit/{artikelid}', name: 'items-edit')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function editItemsAction(Request $request, int $artikelid): Response
    {
        // dd(\Pimcore\Model\DataObject\Customer::getById($userid));
        return $this->render('items/items_edit.html.twig');
    }

    /**
     * @param Request $request
     * @param int $userid
     * @return Response
     */
    #[Route('/items/items-delete/{artikelid}', name: 'items-delete')]
    #[IsGranted("IS_AUTHENTICATED")]
    public function deleteItemsAction(Request $request, int $artikeld): Response
    {
        // $user->delete();

        return $this->redirectToRoute("items");
    }
}
