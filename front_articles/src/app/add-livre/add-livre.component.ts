import { Component, OnInit } from '@angular/core';
import { Livre } from '../models/livre';
import { Categorie } from '../models/categorie';
import { Auteur } from '../models/auteur';
import { ApiService } from '../services/api.service';

@Component({
  selector: 'app-add-livre',
  templateUrl: './add-livre.component.html',
  styleUrl: './add-livre.component.css'
})
export class AddLivreComponent implements OnInit {
  livre: Livre = new Livre('', new Date(), '', '',  0, []);
      categories: Categorie[] = [];
      auteurs: Auteur[] = [];
      info: string = "";
    
      constructor(private apiService: ApiService) {}
    
      ngOnInit(): void {
        this.apiService.getCategories().subscribe((data: Categorie[]) => {
          this.categories = data;
        });
        this.apiService.getAuteurs().subscribe((data: Auteur[]) => {
          this.auteurs = data;
        });
      }
    
      onSubmit() {
        this.apiService.addLivre(this.livre).subscribe(
          result => {
            console.log('Livre ajouté', result);
            this.info = 'Livre créé';
          },
          error => {
            console.error('Erreur lors de l\'ajout du livre', error);
            this.info = 'Erreur lors de l\'ajout du livre';
          }
        );
      }
}
