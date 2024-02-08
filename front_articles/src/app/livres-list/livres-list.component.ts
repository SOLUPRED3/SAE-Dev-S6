import { Component, OnInit } from '@angular/core';
import { Livre } from '../models/livre';
import { ApiService } from '../services/api.service';
import { Auteur } from '../models/auteur';

@Component({
  selector: 'app-livres-list',
  templateUrl: './livres-list.component.html',
  styleUrls: ['./livres-list.component.css']
})
export class LivresListComponent implements OnInit {
  livres: Livre[] = [];
  auteurs: Auteur[] = [];
  filteredLivres: Livre[] = [];
  searchQuery: string = '';

  constructor(private apiService: ApiService) {}

  ngOnInit(): void {
    this.apiService.getLivres().subscribe((data: Livre[]) => {
      this.livres = data;
      this.applyFilter(); // Appliquer le filtre initialement
    });
    this.apiService.getAuteurs().subscribe((data: Auteur[]) => { 
      this.auteurs = data;
    });
  }

  // Fonction de recherche de livre
  applyFilter(): void {
    if (!this.searchQuery) {
      this.filteredLivres = this.livres;
    } else {
      this.filteredLivres = this.livres.filter(livre =>
        livre.titre.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    }
  }

  // Fonction pour obtenir le nom de l'auteur
  getAuteurName(auteurId: number): string {
    const auteur = this.auteurs.find(a => a.id === auteurId);
    return auteur ? `${auteur.nom} ${auteur.prenom}` : '';
  }

  // Fonction appelée lorsqu'une sélection est effectuée dans la recherche
  onSearchChange(): void {
    this.applyFilter();
  }

  // Fonction appelée lorsqu'un livre est sélectionné
  onLivreSelected(): void {
    this.applyFilter();
  }
}
