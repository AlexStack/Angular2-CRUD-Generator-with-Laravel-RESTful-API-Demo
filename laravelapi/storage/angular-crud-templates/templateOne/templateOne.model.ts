import { FormControl, Validators } from '@angular/forms';

export interface TemplateOneModel {
    id?: number;
    name?: string;
    //AngularCrud-API-MODEL
    //[propName: string]: any;
}

export const TemplateOneFormFields = {
    //AngularCrud-FORM-FIELDS
}


/* Uncomment and change below lines if your API return nested format data */
// export interface TemplateOneApiModel<T> {
//     data: T;
//     success: boolean;
//     message: string;
// }

/* Uncomment below lines if your API return flat format data */
export interface TemplateOneApiModel<T> extends TemplateOneModel {
    //AngularCrud-API-LIST-MODEL
}

export interface TemplateOnePaginatedApiModel<T> extends TemplateOneApiModel<T[]> {
    meta: {
        total: number;
        count: number;
        last_page: number;
        per_page: number;
        current_page: number;
        total_pages: number;
    }
}

