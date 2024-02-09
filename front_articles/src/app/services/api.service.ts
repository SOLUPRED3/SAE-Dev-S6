import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Categorie } from '../models/categorie';
import { Livre } from '../models/livre';
import { Auteur } from '../models/auteur';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  private apiUrl = 'https://127.0.0.1:8008/api'; // URL de notre API

  constructor(
    private http: HttpClient
  ) {}

  // Lister les catégories
  getCategories(): Observable<Categorie[]> {
    return this.http.get<Categorie[]>(`${this.apiUrl}/categorie`);
  }

  // Lister les livres
  getLivres(): Observable<Livre[]> {
    return this.http.get<Livre[]>(`${this.apiUrl}/livre`);
  }

  // Ajouter un livre
  addLivre(livre: Livre): Observable<Livre> {
    return this.http.post<Livre>(`${this.apiUrl}/livre`, livre);
  }

  getLivre(id: number): Observable<Livre> {
    return this.http.get<Livre>(`${this.apiUrl}/livre/${id}`);
  }

  // Lister les auteurs
  getAuteurs(): Observable<Auteur[]> {
    return this.http.get<Auteur[]>(`${this.apiUrl}/auteur`);
  }
}