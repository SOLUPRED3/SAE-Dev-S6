import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './home/home.component';
import { CategoriesListComponent } from './categories-list/categories-list.component';
import { LivresListComponent } from './livres-list/livres-list.component';
import { AddLivreComponent } from './add-livre/add-livre.component';
import { AuteursListComponent } from './auteurs-list/auteurs-list.component';

const routes: Routes = [
  { path: '',            component: HomeComponent },
  { path: 'categories',  component: CategoriesListComponent },
  { path: 'livres',    component: LivresListComponent },
  { path: 'add-livre', component: AddLivreComponent },
  { path: 'auteurs', component: AuteursListComponent }
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
