import { ModuleWithProviders, NgModule } from "@angular/core";
import { CommonModule } from '@angular/common';
import { ReactiveFormsModule, FormsModule } from '@angular/forms';
// datatable
import { GenericTableModule } from '@angular-generic-table/core';
import { RouterModule } from '@angular/router';

import { TemplateOneIndexComponent } from './templateOne-index.component';
import { TemplateOneShowComponent } from './templateOne-show.component';
import { TemplateOneEditComponent } from './templateOne-edit.component';

// select
import { NgSelectModule } from '@ng-select/ng-select';

@NgModule({
  imports: [
    CommonModule,
    ReactiveFormsModule,
    GenericTableModule,
    RouterModule,
    FormsModule,
    NgSelectModule
  ],
  declarations: [
    TemplateOneIndexComponent,
    TemplateOneShowComponent,
    TemplateOneEditComponent]
})
export class TemplateOneModule { }
