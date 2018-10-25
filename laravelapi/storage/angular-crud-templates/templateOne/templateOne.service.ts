import { Observable } from 'rxjs';
import { HttpParams, HttpClient } from '@angular/common/http';
import { Router } from '@angular/router';
import { Injectable } from '@angular/core';
import { TemplateOneModel, TemplateOneApiModel, TemplateOnePaginatedApiModel } from './templateOne.model';

@Injectable({
  providedIn: 'root'
})
export class TemplateOneService {

  private api_path = 'AngularCrudApiPath';

  constructor(private httpClient: HttpClient, private router: Router) { }

  index(): Observable<TemplateOneApiModel<TemplateOneModel[]>> {
    return this.httpClient.get<TemplateOneApiModel<TemplateOneModel[]>>(this.api_path);
  }


  search(params: { [name: string]: string } | HttpParams): Observable<TemplateOneApiModel<TemplateOneModel[]>> {
    return this.httpClient.get<TemplateOneApiModel<TemplateOneModel[]>>(this.api_path, { params: params });
  }

  searchPaginated(params: { [name: string]: string } | HttpParams): Observable<TemplateOnePaginatedApiModel<TemplateOneModel>> {
    return this.httpClient.get<TemplateOnePaginatedApiModel<TemplateOneModel>>(this.api_path, { params: params });
  }

  show(id: number): Observable<TemplateOneApiModel<TemplateOneModel>> {
    return this.httpClient.get<TemplateOneApiModel<TemplateOneModel>>(this.api_path + '/' + id);
  }

  store(form_data: any): Observable<TemplateOneApiModel<TemplateOneModel>> {
    return this.httpClient.post<TemplateOneApiModel<TemplateOneModel>>(this.api_path, form_data);
  }

  update(form_data: any, id: number): Observable<TemplateOneApiModel<TemplateOneModel>> {
    return this.httpClient.put<TemplateOneApiModel<TemplateOneModel>>(this.api_path + '/' + id, form_data);
  }

  updateWithPostMethod(form_data: any, id: number): Observable<TemplateOneApiModel<TemplateOneModel>> {
    return this.httpClient.post<TemplateOneApiModel<TemplateOneModel>>(this.api_path + '/' + id, form_data);
  }

  delete(id: number) {
    return this.httpClient.delete(this.api_path + '/' + id);
  }



  edit(id: number): Observable<TemplateOneApiModel<TemplateOneModel>> {
    return this.httpClient.get<TemplateOneApiModel<TemplateOneModel>>(this.api_path + '/' + id);
  }


}
