import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';



//Test40 component import files
import { Test40Route } from "./test40/test40.route";
import { Test40IndexComponent } from "./test40/test40-index.component";
import { Test40EditComponent } from "./test40/test40-edit.component";
import { Test40ShowComponent } from "./test40/test40-show.component";
import { Test40Resolver } from "./test40/test40.resolver";

//Test27 component import files
import { Test27Route } from "./test27/test27.route";
import { Test27IndexComponent } from "./test27/test27-index.component";
import { Test27EditComponent } from "./test27/test27-edit.component";
import { Test27ShowComponent } from "./test27/test27-show.component";
import { Test27Resolver } from "./test27/test27.resolver";

//Test75 component import files
import { Test75Route } from "./test75/test75.route";
import { Test75IndexComponent } from "./test75/test75-index.component";
import { Test75EditComponent } from "./test75/test75-edit.component";
import { Test75ShowComponent } from "./test75/test75-show.component";
import { Test75Resolver } from "./test75/test75.resolver";
//AngularCRUD-IMPORT-ROUTE-COMPONENT

const routes: Routes = [


    
//Test40 route paths
{ path: Test40Route.index, component: Test40IndexComponent},
{ path: Test40Route.create, component: Test40EditComponent },
{ path: Test40Route.show, component: Test40ShowComponent},
{ path: Test40Route.edit, component: Test40EditComponent, resolve: {test40Resolver: Test40Resolver}},
{ path: Test40Route.delete, component: Test40EditComponent},

//Test27 route paths
{ path: Test27Route.index, component: Test27IndexComponent},
{ path: Test27Route.create, component: Test27EditComponent },
{ path: Test27Route.show, component: Test27ShowComponent},
{ path: Test27Route.edit, component: Test27EditComponent, resolve: {test27Resolver: Test27Resolver}},
{ path: Test27Route.delete, component: Test27EditComponent},

//Test75 route paths
{ path: Test75Route.index, component: Test75IndexComponent},
{ path: Test75Route.create, component: Test75EditComponent },
{ path: Test75Route.show, component: Test75ShowComponent},
{ path: Test75Route.edit, component: Test75EditComponent, resolve: {test75Resolver: Test75Resolver}},
{ path: Test75Route.delete, component: Test75EditComponent},
//AngularCRUD-ROUTE-PATH

];

@NgModule({
    imports: [RouterModule.forRoot(routes)],
    exports: [RouterModule]
})

export class AppRoutingModule { }