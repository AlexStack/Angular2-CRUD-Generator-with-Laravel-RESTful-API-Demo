import { tap } from 'rxjs/operators';
import { FormGroup, FormControl, Validators, FormArray, FormBuilder } from '@angular/forms';

import { Component, OnInit } from '@angular/core';
import { TemplateOneService } from './templateOne.service';
import { GtConfig } from '@angular-generic-table/core';
import { ActivatedRoute, Router, UrlSegment, Params } from '@angular/router';
import { TemplateOneRoute } from './templateOne.route';
import { TemplateOneModel, TemplateOneFormFields } from './templateOne.model';


@Component({
  selector: 'app-templateOne-edit',
  templateUrl: './templateOne-edit.component.html',
  styleUrls: ['./templateOne.component.scss']
})


export class TemplateOneEditComponent implements OnInit {


  main_form: FormGroup;

  main_result: TemplateOneModel;

  error_msg: string;

  edit_record_id: number = 0;

  route_link = TemplateOneRoute;

  last_route_part: string = '';

  form_fields = TemplateOneFormFields;


  public dataTableConfig: GtConfig<any>;


  constructor(
    private templateOneService: TemplateOneService,
    private fb: FormBuilder,
    private activatedRoute: ActivatedRoute,
    private router: Router) {

    this.main_form = new FormGroup(this.form_fields);

  }

  ngOnInit() {
    this.activatedRoute.url.subscribe((url_parts: UrlSegment[]) => {
      this.last_route_part = url_parts[url_parts.length - 1].toString();
    });
    this.activatedRoute.params.subscribe((params: Params) => {
      this.edit_record_id = Number(params['id']);
    });
    if (this.last_route_part == 'create') {
      this.create();
    } else if (this.last_route_part == 'edit') {
      //this.edit(this.edit_record_id);

      // use resolver to get the initail data
      this.activatedRoute.data.pipe(
        tap((rs) => {
          if (rs.templateOneResolver) {
            let result = rs.templateOneResolver;
            this.main_result = AngularCrud_API_MAIN_RESULT;
            this.edit_record_id = this.main_result.id;
            this.main_form.patchValue(this.main_result);
          }
          //console.log(rs);
        })
      ).subscribe();

    } else if (this.last_route_part == 'delete') {
      this.delete(this.edit_record_id);
    }
  }


  onSubmit() {

    if (this.main_form.valid == false) {
      console.log(this.main_form);
      this.error_msg = 'Something wrong in the form, please fix it first:';
      const controls = this.main_form.controls;
      for (const name in controls) {
        if (controls[name].invalid) {
          this.error_msg += '' + name + ' ';
        }
      }
      return false;
    }

    if (this.edit_record_id == 0) {
      this.store();
    } else {
      this.update(this.edit_record_id);
    }
  }

  create() {

    //console.log(this.main_form);

    this.edit_record_id = 0;

    // let new_form_obj: { [k: string]: any } = {}
    // for (let form_key in this.main_form.value) {
    //   new_form_obj[form_key] = null;
    // }
    // this.main_form.patchValue(new_form_obj);

  }

  // edit(id: number) {
  //   this.templateOneService.edit(id).pipe(
  //     tap(result => {
  //       this.main_result = result;
  //       this.edit_record_id = result.id;
  //       this.main_form.patchValue(result);
  //     }, error => {
  //       if (error.error && error.error.message) {
  //         this.error_msg = error.error.message;
  //       } else {
  //         this.error_msg = 'Get Data Failure, Please report to Admin';
  //       }
  //     })
  //   ).subscribe();

  // }


  store() {
    const form_data = this.main_form.value;
    //console.log(form_data);
    this.templateOneService.store(form_data)
      .subscribe(
        result => {
          // console.log('Return Form API:');
          // console.log(result);

          this.router.navigate([TemplateOneRoute.index]);
        }, error => {
          if (error.error && error.error.message) {
            this.error_msg = error.error.message;
          } else {
            this.error_msg = 'Create Failure, Please report to Admin';
          }
        }
      );
  }

  update(id: number) {
    let form_data = this.main_form.value;
    /** use new FormData() for upload file */
    // let form_data = new FormData();
    // for (let form_key in this.main_form.value) {
    //   //console.log(form_key + '=' + this.main_form.value[form_key]);
    //   if (form_key == 'avatar_file') {
    //     form_data.append("avatar_file", this.selected_file);
    //   } else {
    //     if (this.main_form.value[form_key] !== null) {
    //       form_data.append(form_key, this.main_form.value[form_key]);
    //     }
    //   }
    // }
    // form_data.append("_method", 'PUT'); // then need change to post method

    this.templateOneService.update(form_data, id)
      .subscribe(
        (result) => {
          this.main_result = AngularCrud_API_MAIN_RESULT;
          this.router.navigate([TemplateOneRoute.index, this.edit_record_id]);
        }, error => {
          if (error.error && error.error.message) {
            this.error_msg = error.error.message;
          } else {
            this.error_msg = 'Update Failure, Please report to Admin';
          }
        }
      );
  }

  delete(id: number) {
    if (!confirm("Are you sure to delete ? ")) {
      this.router.navigate([TemplateOneRoute.index, id]);
      return false;
    }
    this.templateOneService.delete(id)
      .subscribe(
        result => {

          this.router.navigate([TemplateOneRoute.index]);
        }, error => {
          if (error.error && error.error.message) {
            this.error_msg = error.error.message;
          } else {
            this.error_msg = 'Delete Failure, Please report to Admin';
          }
        }
      );
  }




}
