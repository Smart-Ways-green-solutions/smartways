<?php

/**
 * Inheritance: no
 * Variants: no
 *
 * Fields Summary:
 * - localizedfields [localizedfields]
 * -- name [input]
 * -- slug [urlSlug]
 * -- externalUrl [input]
 * -- showSite [manyToOneRelation]
 * - lastUpdate [input]
 * - internalID [input]
 */

return \Pimcore\Model\DataObject\ClassDefinition::__set_state(array(
   'dao' => NULL,
   'id' => 'CAT',
   'name' => 'Category',
   'title' => '',
   'description' => '',
   'creationDate' => NULL,
   'modificationDate' => 1708863056,
   'userOwner' => 2,
   'userModification' => 2,
   'parentClass' => '\\Pimcore\\Bundle\\EcommerceFrameworkBundle\\Model\\AbstractCategory',
   'implementsInterfaces' => '',
   'listingParentClass' => '',
   'useTraits' => '',
   'listingUseTraits' => '',
   'encryption' => false,
   'encryptedTables' => 
  array (
  ),
   'allowInherit' => false,
   'allowVariants' => false,
   'showVariants' => false,
   'layoutDefinitions' => 
  \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
     'name' => 'pimcore_root',
     'type' => NULL,
     'region' => NULL,
     'title' => NULL,
     'width' => 0,
     'height' => 0,
     'collapsible' => false,
     'collapsed' => false,
     'bodyStyle' => NULL,
     'datatype' => 'layout',
     'children' => 
    array (
      0 => 
      \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
         'name' => 'Layout',
         'type' => NULL,
         'region' => NULL,
         'title' => '',
         'width' => '',
         'height' => '',
         'collapsible' => false,
         'collapsed' => false,
         'bodyStyle' => '',
         'datatype' => 'layout',
         'children' => 
        array (
          0 => 
          \Pimcore\Model\DataObject\ClassDefinition\Layout\Tabpanel::__set_state(array(
             'name' => 'Layout',
             'type' => NULL,
             'region' => NULL,
             'title' => '',
             'width' => '',
             'height' => '',
             'collapsible' => false,
             'collapsed' => false,
             'bodyStyle' => '',
             'datatype' => 'layout',
             'children' => 
            array (
              0 => 
              \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'name' => 'Layout',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Settings',
                 'width' => '',
                 'height' => '',
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'children' => 
                array (
                  0 => 
                  \Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                     'name' => 'localizedfields',
                     'title' => '',
                     'tooltip' => NULL,
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => NULL,
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => true,
                     'visibleSearch' => true,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'children' => 
                    array (
                      0 => 
                      \Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'name' => 'name',
                         'title' => 'Name',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'fieldtype' => '',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValue' => NULL,
                         'columnLength' => 190,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'width' => '',
                         'defaultValueGenerator' => '',
                      )),
                      1 => 
                      \Pimcore\Model\DataObject\ClassDefinition\Data\UrlSlug::__set_state(array(
                         'name' => 'slug',
                         'title' => 'Slug',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => true,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'fieldtype' => '',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'domainLabelWidth' => NULL,
                         'action' => 'App\\Controller\\CategoryController::showCategoryAction',
                         'availableSites' => 
                        array (
                        ),
                         'width' => '',
                         'activeDispatchingEvents' => 
                        array (
                        ),
                      )),
                      2 => 
                      \Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'name' => 'externalUrl',
                         'title' => 'External Url',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'fieldtype' => '',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValue' => NULL,
                         'columnLength' => 190,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'width' => '',
                         'defaultValueGenerator' => '',
                      )),
                      3 => 
                      \Pimcore\Model\DataObject\ClassDefinition\Data\ManyToOneRelation::__set_state(array(
                         'name' => 'showSite',
                         'title' => 'Show Site',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'fieldtype' => '',
                         'relationType' => true,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'classes' => 
                        array (
                        ),
                         'displayMode' => 'grid',
                         'pathFormatterClass' => '',
                         'assetInlineDownloadAllowed' => false,
                         'assetUploadPath' => '',
                         'allowToClearRelation' => true,
                         'objectsAllowed' => false,
                         'assetsAllowed' => false,
                         'assetTypes' => 
                        array (
                        ),
                         'documentsAllowed' => true,
                         'documentTypes' => 
                        array (
                        ),
                         'width' => '',
                      )),
                    ),
                     'region' => NULL,
                     'layout' => NULL,
                     'maxTabs' => NULL,
                     'border' => false,
                     'provideSplitView' => false,
                     'tabPosition' => 'top',
                     'hideLabelsWhenTabsReached' => NULL,
                     'referencedFields' => 
                    array (
                    ),
                     'permissionView' => NULL,
                     'permissionEdit' => NULL,
                     'labelWidth' => 100,
                     'labelAlign' => 'left',
                     'fieldDefinitionsCache' => NULL,
                  )),
                  1 => 
                  \Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'lastUpdate',
                     'title' => 'Last Update',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => true,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'defaultValue' => NULL,
                     'columnLength' => 190,
                     'regex' => '',
                     'regexFlags' => 
                    array (
                    ),
                     'unique' => false,
                     'showCharCount' => false,
                     'width' => '',
                     'defaultValueGenerator' => '',
                  )),
                  2 => 
                  \Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'internalID',
                     'title' => 'Internal ID',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => true,
                     'index' => true,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'defaultValue' => NULL,
                     'columnLength' => 190,
                     'regex' => '',
                     'regexFlags' => 
                    array (
                    ),
                     'unique' => false,
                     'showCharCount' => false,
                     'width' => '',
                     'defaultValueGenerator' => '',
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'fieldtype' => 'panel',
                 'layout' => NULL,
                 'border' => false,
                 'icon' => '',
                 'labelWidth' => 100,
                 'labelAlign' => 'left',
              )),
            ),
             'locked' => false,
             'blockedVarsForExport' => 
            array (
            ),
             'fieldtype' => 'tabpanel',
             'border' => false,
             'tabPosition' => 'top',
          )),
        ),
         'locked' => false,
         'blockedVarsForExport' => 
        array (
        ),
         'fieldtype' => 'panel',
         'layout' => NULL,
         'border' => false,
         'icon' => '',
         'labelWidth' => 100,
         'labelAlign' => 'left',
      )),
    ),
     'locked' => false,
     'blockedVarsForExport' => 
    array (
    ),
     'fieldtype' => 'panel',
     'layout' => NULL,
     'border' => false,
     'icon' => NULL,
     'labelWidth' => 100,
     'labelAlign' => 'left',
  )),
   'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/flow_chart.svg',
   'group' => '',
   'showAppLoggerTab' => false,
   'linkGeneratorReference' => '',
   'previewGeneratorReference' => '',
   'compositeIndices' => 
  array (
  ),
   'showFieldLookup' => false,
   'propertyVisibility' => 
  array (
    'grid' => 
    array (
      'id' => true,
      'key' => false,
      'path' => true,
      'published' => true,
      'modificationDate' => true,
      'creationDate' => true,
    ),
    'search' => 
    array (
      'id' => true,
      'key' => false,
      'path' => true,
      'published' => true,
      'modificationDate' => true,
      'creationDate' => true,
    ),
  ),
   'enableGridLocking' => false,
   'deletedDataComponents' => 
  array (
  ),
   'blockedVarsForExport' => 
  array (
  ),
   'fieldDefinitionsCache' => 
  array (
  ),
   'activeDispatchingEvents' => 
  array (
  ),
));