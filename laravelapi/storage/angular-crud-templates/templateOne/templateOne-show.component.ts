
import { Component, OnInit } from '@angular/core';
import { TemplateOneService } from './templateOne.service';
import { GtConfig } from '@angular-generic-table/core';
import { ActivatedRoute, Router, Params } from '@angular/router';
import { TemplateOneModel, TemplateOneApiModel } from './templateOne.model';
import { TemplateOneRoute } from './templateOne.route';
import { tap } from 'rxjs/operators';

@Component({
  selector: 'app-templateOne-show',
  templateUrl: './templateOne-show.component.html',
  styleUrls: ['./templateOne.component.scss']
})
export class TemplateOneShowComponent implements OnInit {

  main_result: TemplateOneModel;

  route_link = TemplateOneRoute;

  error_msg: string;
  public dataTableConfig: GtConfig<any>;


  constructor(private templateOneService: TemplateOneService, private route: ActivatedRoute, private router: Router) { }

  ngOnInit() {

    this.route.params.subscribe((params: Params) => {
      this.show(Number(params['id']));
    });
    //this.show(Number(this.route.snapshot.paramMap.get('id')));

  }



  show(id: number) {

    this.templateOneService.show(id).pipe(
      tap(result => {
        this.main_result = AngularCrud_API_MAIN_RESULT;
      }, error => {
        if (error.error && error.error.message) {
          this.error_msg = error.error.message;
        } else {
          this.error_msg = 'Failure, Please report to Admin';
        }
      })
    ).subscribe();
  }


}
