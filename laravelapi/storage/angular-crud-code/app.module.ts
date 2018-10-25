
import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { AppRoutingModule } from './app-routing.module';
import { RouterModule } from '@angular/router';
import { HttpClientModule } from '@angular/common/http';


import { AppComponent } from './app.component';

import { GenericTableModule } from '@angular-generic-table/core';
import { Observable, Subject, asapScheduler, pipe, of, from, interval, merge, fromEvent } from 'rxjs';

//AngularCRUD-IMPORT-MODULE

@NgModule({
	imports: [
		BrowserModule,
		RouterModule,
		AppRoutingModule,
		FormsModule,
		HttpClientModule,
		//AngularCRUD-NG-MODULE
	],
	declarations: [
		AppComponent
	],
	bootstrap: [AppComponent]
})
export class AppModule { }

