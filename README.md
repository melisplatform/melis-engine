# melis-engine

MelisEngine provides a set of services and classes to access the CMS' pages and datas.

## Getting Started

These instructions will get you a copy of the project up and running on your machine.  

### Prerequisites

None  

### Installing

Run the composer command:
```
composer require melisplatform/melis-engine
```

### Database    

Database model is accessible on the MySQL Workbench file:  
/melis-engine/install/sql/model  
Database will be installed through composer and its hooks.  
In case of problems, SQL files are located here:  
/melis-engine/install/sql  


## Tools & Elements provided

* All Melis CMS database model  
* Services to access the page and tree system  
* Default Melis templating plugin abstract class  
* Page microservices  


## Running the code

### MelisEngine Services  

MelisEngine provides many services to be used in other modules:  

* MelisPageService  
Provides services to get all datas from a page.
File: /melis-engine/src/Service/MelisPageService.php  
```
// Get the service
$melisPage = $this->getServiceManager()->get('MelisEnginePage');
// Get all datas of this page
$datasPageRes = $melisPage->getDatasPage($siteMainPage); 
```

* MelisTreeService  
Provides services to get sets of pages based on the tree of pages.  
Meant to deliver parent pages, breadcrumb, generate menus, generate URLs, etc.   
File: /melis-engine/src/Service/MelisTreeService.php  
```
// Get the service
$treeSrv = $this->getServiceManager()->get('MelisEngineTree');
// Get the breadcrumb
$pageBreadcrumb = $treeSrv->getPageBreadcrumb($pageId, 0, true);
```

* MelisSearch  
This service deals with search on the Melis Platform hosted website.  
Search is done using Zend_Search.  
File: /melis-engine/src/Service/MelisSearchService.php  
```
// Get the service
$searchSvc = $this->getServiceManager()->get('MelisSearch');
// Search
$searchresults = $searchSvc->search($keyword, $moduleName, true);
```

### Melis CMS database models

All models used my the platform to access the CMS part of the database are located  in this module.  
Folder: /melis-engine/src/Model  


### Melis Templating Plugin Abstract Class  

Melis Platform offers a plugin system for edition of pages.  
All plugins are built on the ZF2 Controller plugin system, and all plugins must extend this class too as it provides many default and awaited methods.  
File: /melis-engine/src/Controller/Plugin/MelisTemplatingPlugin.php  

**[See Full documentation on templating plugins here](https://www.melistechnology.com/MelisTechnology/resources/documentation/front-office/create-a-templating-plugin/Principle)**


## Authors

* **Melis Technology** - [www.melistechnology.com](https://www.melistechnology.com/)

See also the list of [contributors](https://github.com/melisplatform/melis-engine/contributors) who participated in this project.


## License

This project is licensed under the OSL-3.0 License - see the [LICENSE.md](LICENSE.md) file for details