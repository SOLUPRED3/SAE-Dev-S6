import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';

import { HttpClientModule } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { HomeComponent } from './home/home.component';
import { CategoriesListComponent } from './categories-list/categories-list.component';
import { LivresListComponent } from './livres-list/livres-list.component';
import { AuteursListComponent } from './auteurs-list/auteurs-list.component';
import { AddLivreComponent } from './add-livre/add-livre.component';
import { LivreDetailComponent } from './livre-detail/livre-detail.component';

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    CategoriesListComponent,
    LivresListComponent,
    AuteursListComponent,
    AddLivreComponent,
    LivreDetailComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    FormsModule,
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
