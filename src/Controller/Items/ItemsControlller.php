<?php

namespace App\Controller\Items;

use App\Controller\BaseController;
use App\Model\Customer;
use Exception;
use Pimcore\Model\Asset\Folder as AssetFolder;
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
use Pimcore\Model\DataObject\Supplier;
use Pimcore\Model\DataObject\Tags;
use Pimcore\Model\DataObject\Warehouse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
        $limit = 2;
        $offset = ($page - 1) * $limit;

        $products = [];
        $allProducts = Product::getList();
        
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


        switch ($sort) {
            case 'price_asc':
                $allProducts->setOrderKey("price");
                $allProducts->setOrder("ASC");
                break;
            case 'price_desc':
                $allProducts->setOrderKey("price");
                $allProducts->setOrder("DESC");
                break;
            // case 'inventory_asc':
            //     $allProducts->setOrderKey("inventory");
            //     $allProducts->setOrder("ASC");
            //     break;
            // case 'inventory_desc':
            //     $allProducts->setOrderKey("inventory");
            //     $allProducts->setOrder("DESC");
            //     break;
        }

        $totalProducts = count($allProducts);
        $totalPages = max(1, ceil($totalProducts / $limit));

        $allProducts->setLimit($limit)->setOffset($offset);

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

            $product = [
                "id" => $item->getId(),
                "name" => $item->getItemname(),
                "image" => $firstImage,
                "description" => $item->getShortDescription(),
                "tags" => $tags
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
    #[Route('/items/items-create', name: 'items-create', methods: ['GET', 'POST'])]
    #[IsGranted("IS_AUTHENTICATED")]
    public function createItemsAction(Request $request): Response
    {
        if($request->isMethod('POST')){

            $formData = $request->request->all();
            // $formFields = array_keys($formData);

            try{
                $product = new Product();
                $product->setPath("/Products");
                $productName = $request->get("Itemname");
                if($productName) {
                    $product->setKey($productName);
                }
                $productsFolder = Folder::getByPath("/Products");
                $product->setParentId($productsFolder->getId());

                // foreach($formData as $key => $item) {
                //     echo $key . " => ";
                //     var_dump($item);
                //     echo "<br>";
                // }

                foreach($formData as $key => $item) {
                    $setterMethod = "set" . ucfirst($key);
                    echo $key;
                    echo "<br>";
                    $field = $product->getClass()->getFieldDefinition($key);
                    if($field) {
                        $fieldType = $field->getFieldType();
                        echo $field->getName()." => ".$fieldType;
                        echo "<br>";
                        echo $field->getName()." => ";
                        var_dump($item);
                        echo "<br><br>";
                    }

                    if($fieldType == "fieldcollections" && $key == "suppliers" && !empty($item)) {
                        echo "field collection entered";
                        $fieldCollection = new \Pimcore\Model\DataObject\Fieldcollection();
    
                        foreach ($item as $subKey => $supplierData) {
                            echo $subKey;
                            // Create a new Field Collection Item of type "Supplier"
                            $supplierItem = new \Pimcore\Model\DataObject\Fieldcollection\Data\SuppliersFieldCollection();
                            
                            $supplier = Supplier::getById($subKey);
                            $supplierItem->setSupplier($supplier);
                            $supplierItem->setSupplierNETprice($supplierData['supplierNETprice']);
                            $supplierItem->setSupplierItemID($supplierData['supplierItemID']);
                            $supplierItem->setTypicalDeliveryTime($supplierData['TypicalDeliveryTime']);
                            
                            // Add the item to the Field Collection
                            $fieldCollection->add($supplierItem);
                        }
                        
                        // Assign Field Collection to the object
                        $product->$setterMethod($fieldCollection);
                    } elseif ($fieldType == "manyToOneRelation" || $fieldType == "manyToManyObjectRelation") {
                        $fieldClasses = $field->getClasses();
                        $objectClass = $fieldClasses[0]["classes"];
                    
                        // Fully Qualified Class Name
                        $fqClassName = "\\Pimcore\\Model\\DataObject\\" . $objectClass;
                    
                        if ($fieldType == "manyToOneRelation") {
                            $object = $fqClassName::getById($item);
                            $product->$setterMethod($object);
                        } elseif ($fieldType == "manyToManyObjectRelation") {
                            $objects = [];
                            // var_dump($item);
                            foreach ($item as $id) {
                                $obj = $fqClassName::getById($id);
                                if ($obj) {
                                    $objects[] = $obj;
                                }
                            }
                            $product->$setterMethod($objects);
                        }
                    } else {
                        if (!empty($item)) {
                            // echo $setterMethod;
                            // echo "<br>";
                            $product->$setterMethod($item);
                        }
                    }
                }

                // Handling images field
                if (!empty($_FILES['images']['name'][0])) {  
                    $imageGallery = new \Pimcore\Model\DataObject\Data\ImageGallery();  
                    $items = [];  
                
                    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {  
                        if ($_FILES['images']['error'][$index] == UPLOAD_ERR_OK) {  
                            $originalFilename = $_FILES['images']['name'][$index];  
                            $extension = pathinfo($originalFilename, PATHINFO_EXTENSION);
                            $baseName = pathinfo($originalFilename, PATHINFO_FILENAME);

                            // Generate a unique filename (timestamp + random number)
                            $uniqueFilename = $baseName . '-' . time() . '-' . rand(1000, 9999) . '.' . $extension;  
                
                            $image = new \Pimcore\Model\Asset\Image();
                            $folder = AssetFolder::getByPath("/product-images");
                            if(!$folder) {
                                $folder = new AssetFolder();
                                $folder->setParentId(1);
                                $folder->setKey("product-images");
                                $folder->save();
                            }
                            $image->setParent($folder);
                            $image->setFilename($uniqueFilename);
                            $image->setData(file_get_contents($tmpName));
                            $image->save();

                            // Convert to Hotspotimage
                            $hotspotImage = new DataHotspotimage();
                            $hotspotImage->setImage($image);
                
                            // Add image to ImageGallery
                            $items[] = $hotspotImage;
                        }
                    }
                
                    $imageGallery->setItems($items);
                    $product->setImages($imageGallery);
                }                


                $product->setPublished(true);
                $product->save();

                return new Response("Submitted successfully");
            } catch(Exception $e){
                echo $e->getMessage();
                return new Response("Problem adding product");
            }

            return new Response("Submitted successfully");
        } else {
            $dropdownFields = [];
            $product = new Product();
            $productClass = ClassDefinition::getByName("Product");
            $productFields = $productClass->getFieldDefinitions();
            foreach($productFields as $field) {
                if (in_array(get_class($field), [ManyToOneRelation::class, ManyToManyObjectRelation::class, Select::class, Fieldcollections::class])) {
                    // echo $field->getName();
                    // echo "<br>";
                    if($field instanceof Select) {
                        // var_dump(get_class_methods($field));
                        if($field->getOptionsProviderData()) {
                            $options = DataObject\Service::getOptionsForSelectField($product, $field->getName());
                            $options = array_keys($options);
                        } else {
                            // var_dump($field->getOptions());
                            $options = array_column($field->getOptions(), "key");
                        }
                        // echo $field->getName();
                        // var_dump($options);
                        // echo "<br>";
                        $dropdownFields[] = [
                            $field->getName() => is_null($options) ? [] : $options
                        ];
                    }
                    if (in_array(get_class($field), [ManyToOneRelation::class, ManyToManyObjectRelation::class])) {
                        $allowedClasses = $field->getClasses();
                        // var_dump($allowedClasses);
                        foreach($allowedClasses as $className) {
                            $classes = $className["classes"];
                            $fetchedClass = "Pimcore\\Model\\DataObject\\$classes";
                            if(class_exists($fetchedClass)) {
                                $listClass = $fetchedClass . "\\Listing";
                                $objectList = new $listClass();

                                $objects = $objectList->load();
                                $objectNames = [];

                                foreach($objects as $object) {
                                    $objectId = $object->getId();
                                    if($classes == "Product") {
                                        $objectName = $object->getItemname();
                                        if(is_null($objectName)) {
                                            $objectName = $object->getKey();
                                        }
                                    } else {
                                        $objectName = $object->getName();
                                        if(is_null($objectName)) {
                                            $objectName = $object->getKey();
                                        }
                                    }
                                    $objectNames[] = [
                                        $objectId => $objectName
                                    ];
                                }
                                $dropdownFields[] = [
                                    $classes => $objectNames
                                ];
                            }
                        }
                    }
                    if($field instanceof Fieldcollections && $field->getName() == "suppliers") {
                        $suppliers = Supplier::getList();
                        foreach($suppliers as $supplier) {
                            $supplierId = $supplier->getId();
                            $supplierName = $supplier->getName();
                            $dropdownFields[] = [
                                "Suppliers" => [
                                    $supplierId => $supplierName
                                ]
                            ];
                        }
                    }
                }
            }
            // var_dump($dropdownFields);
        }
        return $this->render('items/items_create.html.twig', [
            "dropdownFields" => $dropdownFields
        ]);
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
                    $productImg = $productImages[0]->getImage(); // Get the actual image
                    $productImg = $productImg->getFullPath(); // Get image path if needed
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
