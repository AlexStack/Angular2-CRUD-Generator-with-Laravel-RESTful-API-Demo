import { Component, OnInit } from '@angular/core';
import { TemplateOneService } from './templateOne.service';
import { GtConfig, GtOptions } from '@angular-generic-table/core';
import { ActivatedRoute, Router } from '@angular/router';
import { TemplateOneRoute } from './templateOne.route';
import { TemplateOneModel, TemplateOneApiModel } from './templateOne.model';
import { HttpParams } from '@angular/common/http';
import { tap } from 'rxjs/operators';

@Component({
  selector: 'app-templateOne-index',
  templateUrl: './templateOne-index.component.html',
  styleUrls: ['./templateOne.component.scss']
})
export class TemplateOneIndexComponent implements OnInit {


  main_result: TemplateOneModel[] | TemplateOneApiModel<TemplateOneModel[]>;

  error_msg: string;

  route_link = TemplateOneRoute;

  per_page = 10;  // Show how many rows per page
  // load all data for quick pagination or server end pagination
  // set to false if data record over 2000
  load_all_data = true;

  search_str: string = '';


  public dataTableConfig: GtConfig<any>;
  public dataTableOptions: GtOptions;

  constructor(private templateOneService: TemplateOneService, private route: ActivatedRoute, private router: Router) {

    this.dataTableOptions = {
      lazyLoad: !this.load_all_data,
      cache: false,
      numberOfRows: this.per_page,
      debounceTime: 2000,
      highlightSearch: true
    }

    this.dataTableConfig = {
      settings: [
        {
          objectKey: 'id',
          sort: 'desc',
          columnOrder: 0
        },
        //AngularCrud-DATATABLE-SETTINGS
        {
          objectKey: 'detail_link',
          sort: 'disable',
          columnOrder: 100
        },
        {
          objectKey: 'edit_link',
          sort: 'disable',
          columnOrder: 110
        },
        {
          objectKey: 'delete_link',
          sort: 'disable',
          columnOrder: 120
        },
      ],
      fields: [
        {
          name: 'Id',
          objectKey: 'id',
          columnClass: 'd-none d-md-table-cell'
        },
        //AngularCrud-DATATABLE-FIELDS
        {
          objectKey: 'detail_link',
          name: '',
          value: () => '',
          render: () =>
            '<button type="button" class="btn btn-outline-info btn-sm"><i class="fa fa-file-text-o" aria-hidden="true"></i></button>',
          click: row => this.show(row.id)
        },
        {
          objectKey: 'edit_link',
          name: '',
          value: () => '',
          render: () =>
            '<button type="button" class="btn btn-outline-info btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>',
          click: row => this.edit(row.id)
        },
        {
          objectKey: 'delete_link',
          name: '',
          value: () => '',
          render: () =>
            '<button type="button" class="btn btn-outline-info btn-sm"><i class="fa fa-trash-o" aria-hidden="true"></i></button>',
          click: row => this.delete(row.id)
        },
      ],
    }

  }

  ngOnInit() {

    if (this.load_all_data == false) {
      this.index(1);
      console.log(this.dataTableConfig.info);

      // need implement twice to show the pagination for lazyload
      // don't know the reason, need to fix in the future
      this.index(1);

      console.log(this.dataTableConfig.info);
    } else {
      this.index(0);
    }

  }


  // need to add a debounceTime for this search
  processSearch(q: string) {
    this.search_str = q;
    this.index(1);
  }


  index(page: number = 0) {
    let params = new HttpParams()
      .set('orderBy', 'id')
      .set('sortedBy', 'desc');

    if (page > 0) {
      params = params.set('page', String(page)).set('per_page', String(this.per_page));
    }
    if (this.search_str != '') {
      params = params.set('search', this.search_str).set('searchFields', 'name:ilike');
    }

    this.templateOneService.search(params).pipe(
      tap(result => {
        this.main_result = AngularCrud_API_MAIN_RESULT;
        // if (this.load_all_data == false) {
        //   this.dataTableConfig.info = {
        //     pageCurrent: result.meta.current_page,
        //     pageTotal: result.meta.total_pages,
        //     recordLength: this.per_page,
        //     recordsAll: result.meta.total,
        //     recordsAfterFilter: result.meta.total,
        //     recordsAfterSearch: result.meta.total,
        //     searchTerms: this.search_str
        //     //visibleRecords?: any;
        //   }
        // } else {
        //   //this.dataTableConfig.info = null;
        // }
      }, error => {
        if (error.error && error.error.message) {
          this.error_msg = error.error.message;
        } else {
          this.error_msg = 'Failure, Please report to Admin';
        }
      })
    ).subscribe();
  }

  show(id: number) {
    this.router.navigate([TemplateOneRoute.index, id]);
  }

  edit(id: number) {
    //this.router.navigate([TemplateOneRoute.index, id, 'edit']);
    this.router.navigateByUrl('/' + TemplateOneRoute.edit.replace(':id', String(id)));
  }

  delete(id: number) {
    this.router.navigate([TemplateOneRoute.index, id, 'delete']);
  }

  dataTableTrigger($event: any) {
    switch ($event.name) {
      case 'gt-page-changed-lazy':
        //this.index($event.value.pageCurrent, $event.value.recordLength);
        this.index($event.value.pageCurrent);
        break;
      case 'gt-sorting-applied':
        //console.log($event.value);
        break;
      case 'gt-row-clicked':
        //console.log($event.value);
        //this.show($event.value.row.id);
        break;
      default:
        //console.log($event);
        break;
    }
  }


}
