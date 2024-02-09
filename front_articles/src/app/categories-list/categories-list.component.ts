import { Component, OnInit  } from '@angular/core';
import { Categorie } from '../models/categorie';
import { ApiService } from '../services/api.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-categories-list',
  templateUrl: './categories-list.component.html',
  styleUrl: './categories-list.component.css'
})
export class CategoriesListComponent implements OnInit {
  categories: Categorie[] = [];
 
   constructor(
      private apiService: ApiService,
      private router: Router
    ) {}
 
   ngOnInit(): void {
     this.apiService.getCategories().subscribe((data: Categorie[]) => {
       this.categories = data;
     });
   }

   onLivreSelected(event: Event) {
    const titre = (event.target as HTMLInputElement).value;
    if (titre) {
      this.router.navigate(['/livre', titre]);
    }
  }
  }
