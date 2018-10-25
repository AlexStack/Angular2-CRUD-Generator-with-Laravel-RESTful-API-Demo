import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from "@angular/router";
import { TemplateOneModel, TemplateOneApiModel } from "./templateOne.model";
import { Injectable } from "@angular/core";
import { Observable } from "rxjs";
import { TemplateOneService } from "./templateOne.service";

@Injectable({ providedIn: 'root' })

export class TemplateOneResolver {
    //export class TemplateOneResolver implements Resolve<TemplateOneModel> {

    constructor(private templateOneService: TemplateOneService) {

    }


    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<TemplateOneApiModel<TemplateOneModel>> | Observable<TemplateOneModel> | Promise<TemplateOneModel> | TemplateOneModel {
        const templateOneIdRaw = route.params.id;
        if (templateOneIdRaw) {
            return this.templateOneService.show(parseInt(templateOneIdRaw));
        }
        return undefined;
    }

}