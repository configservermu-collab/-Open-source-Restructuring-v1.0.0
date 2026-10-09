# Matriz de pruebas Ctrl PVP

Objetivo: demostrar que toda ruta de ataque respeta la misma politica de autorizacion.

| Caso | Mapa/contexto | Atacante | Objetivo | Resultado esperado | Ruta a verificar |
|---|---|---|---|---|---|
| 1 | Mapa no-PK | jugador | jugador | bloqueado | `CheckPlayerTarget` |
| 2 | Mapa PK | jugador | jugador | permitido segun PK | `CheckPlayerTarget` |
| 3 | Party | jugador | miembro party | bloqueado si switch activo | party + helper |
| 4 | Guild war | guild A | guild B | permitido segun `m_GuildWarAttackEnable` | `Guild::CheckWar` |
| 5 | Misma guild en guerra | guild A | guild A | bloqueado | regla de guild |
| 6 | Duelo reciproco | jugador | rival | permitido | `Duel::CheckDuel` |
| 7 | Duelo no reciproco | jugador | jugador | regla normal del mapa | `Duel::CheckDuel` |
| 8 | Custom Arena | jugador | jugador | delega en arena | `CustomArena::CheckPlayerTarget` |
| 9 | Chaos Castle activo | jugador | jugador | permitido | estado CC |
| 10 | Chaos Castle no activo | jugador | jugador | bloqueado | estado CC |
| 11 | Castle Siege misma alianza | jugador | jugador | bloqueado segun switch | `CastleJoinSide` |
| 12 | Castle Siege enemigos | jugador | jugador | permitido | `CastleJoinSide` |
| 13 | Dark Spirit | spirit | jugador | misma politica que ataque normal | `DarkSpirit` -> `Attack` |
| 14 | Skill PvP | jugador | jugador | misma politica que ataque normal | `SkillManager` -> `Attack` |
| 15 | CustomAttack online | jugador | jugador | servidor decide | `CustomAttack` -> `Attack` |
| 16 | CustomAttack offline | jugador | jugador | no bypass | `CustomAttack` + `Attack` |
| 17 | Summon | summon | jugador | misma politica | `SummonIndex` |
| 18 | Admin | jugador | administrador | bloqueado | `Authority` |
| 19 | Nivel <= 5 | jugador | jugador | bloqueado | nivel |
| 20 | Objetivo desconectado | jugador | jugador | bloqueado | validacion inicial |

## Criterio de aceptacion

Cada caso debe producir un resultado de servidor observable: dano aplicado, paquete de dano, log de rechazo o estado de vida/SD sin cambio. No basta con ocultar la barra de ataque del cliente.
